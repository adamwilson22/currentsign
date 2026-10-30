<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class PendingSignatureSend
{
    public const SESSION_KEY = 'pending_signature_send';

    public function stashFromRequest(Request $request, int $userId): array
    {
        $file = $request->file('pdf_path');
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $filePath = 'uploads/folders/pending/';
        $destination = public_path($filePath);
        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        $file->move($destination, $fileName);

        $returnUrl = url()->previous() ?: url('/user/dashboard');
        if (! str_starts_with($returnUrl, url('/'))) {
            $returnUrl = url('/user/dashboard');
        }

        $payload = [
            'user_id' => $userId,
            'pdf_path' => $filePath . $fileName,
            'original_name' => $file->getClientOriginalName(),
            'email' => (string) $request->input('email', ''),
            'page' => max(1, (int) $request->input('page', 1)),
            'return_url' => $returnUrl,
            'stashed_at' => now()->toDateTimeString(),
        ];

        session([
            self::SESSION_KEY => $payload,
            'billing_return_url' => $returnUrl,
        ]);

        return $payload;
    }

    public function get(): ?array
    {
        $pending = session(self::SESSION_KEY);
        if (! is_array($pending) || empty($pending['pdf_path'])) {
            return null;
        }

        return $pending;
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Finish a stashed send after the user can send again.
     *
     * @return array{ok:bool,message:?string,signing_link:?string,return_url:?string}
     */
    public function completeIfPossible(int $userId): array
    {
        $pending = $this->get();
        if (! $pending || (int) ($pending['user_id'] ?? 0) !== $userId) {
            return ['ok' => false, 'message' => null, 'signing_link' => null, 'return_url' => null];
        }

        $user = DB::table('users')->where('id', $userId)->first();
        if (! $user) {
            return ['ok' => false, 'message' => null, 'signing_link' => null, 'return_url' => null];
        }

        $gate = app(EntitlementService::class)->canSend($user);
        if (! $gate['ok']) {
            return [
                'ok' => false,
                'message' => $gate['reason'],
                'signing_link' => null,
                'return_url' => $pending['return_url'] ?? url('/user/dashboard'),
            ];
        }

        $absolute = public_path($pending['pdf_path']);
        if (! is_file($absolute)) {
            $this->clear();

            return [
                'ok' => false,
                'message' => 'Saved document was missing. Please upload again.',
                'signing_link' => null,
                'return_url' => $pending['return_url'] ?? url('/user/dashboard'),
            ];
        }

        $data = [
            'user_id' => $userId,
            'email' => $pending['email'] ?? '',
            'pdf_path' => $pending['pdf_path'],
            'status' => 'Awaiting',
        ];
        if (Schema::hasColumn('signatures', 'page')) {
            $data['page'] = (int) ($pending['page'] ?? 1);
        }

        $id = DB::table('signatures')->insertGetId($data);
        app(EntitlementService::class)->recordSendAfterInsert($userId);

        $link = url('/signature?id=' . $id);
        $emailid = $pending['email'] ?? '';
        $mailOk = false;

        if ($emailid !== '') {
            try {
                Mail::send([], [], function ($message) use ($emailid, $link) {
                    $message->to($emailid)
                        ->subject('Signature PDF')
                        ->from(config('mail.from.address', 'sign@currentsign.com'), config('mail.from.name', 'CurrentSign'))
                        ->html("
                            Hi,<br><br>
                            Please sign this document.<br><br>
                            <a href='{$link}' style='padding:10px 15px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px;'>
                                View Document
                            </a>
                        ");
                });
                $mailOk = true;
            } catch (\Throwable $e) {
                \Log::warning('Pending signature invite email failed', [
                    'signature_id' => $id,
                    'email' => $emailid,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $returnUrl = $pending['return_url'] ?? url('/user/dashboard');
        $this->clear();
        session()->forget('billing_return_url');

        $flash = $mailOk
            ? 'Subscription active — document sent to ' . $emailid . '.'
            : 'Subscription active — document saved. Use the signing link if email was not delivered.';

        return [
            'ok' => true,
            'message' => $flash,
            'signing_link' => $link,
            'return_url' => $returnUrl,
        ];
    }
}
