<?php

namespace App\Http\Controllers;

use App\Services\EntitlementService;
use App\Services\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class StripeCheckoutController extends Controller
{
    public function __construct(
        private StripeClient $stripe,
        private EntitlementService $entitlements
    ) {}

    public function checkout(Request $request, string $plan)
    {
        $plan = strtolower($plan);
        $plans = config('stripe.plans');
        if (! isset($plans[$plan])) {
            return redirect('/pricing')->with('error', 'Unknown plan.');
        }

        if (! Auth::check()) {
            session(['stripe_checkout_plan' => $plan]);

            return redirect()->route('login')->with('error', 'Please sign in to upgrade.');
        }

        if ($request->filled('return_url')) {
            session(['billing_return_url' => $request->input('return_url')]);
        } elseif (! session()->has('billing_return_url')) {
            $prev = url()->previous();
            if ($prev && str_starts_with($prev, url('/')) && ! str_contains($prev, '/pricing') && ! str_contains($prev, '/billing/')) {
                session(['billing_return_url' => $prev]);
            }
        }

        $user = Auth::user();
        $planMeta = $plans[$plan];
        $priceId = config('stripe.prices.' . $plan);

        $customerId = $user->stripe_customer_id ?? null;
        if (! $customerId) {
            $customer = $this->stripe->post('/customers', [
                'email' => $user->email,
                'name' => $user->full_name ?? $user->name ?? '',
                'metadata[user_id]' => (string) $user->id,
            ]);
            $customerId = $customer['id'];
            if (Schema::hasColumn('users', 'stripe_customer_id')) {
                DB::table('users')->where('id', $user->id)->update(['stripe_customer_id' => $customerId]);
            }
        }

        $lineItem = $priceId
            ? ['price' => $priceId, 'quantity' => 1]
            : [
                'price_data' => [
                    'currency' => config('stripe.currency', 'usd'),
                    'unit_amount' => $planMeta['amount'],
                    'recurring' => ['interval' => 'month'],
                    'product_data' => [
                        'name' => 'CurrentSign ' . $planMeta['name'],
                        'metadata' => ['plan_id' => $plan],
                    ],
                ],
                'quantity' => 1,
            ];

        // Flatten nested arrays for Stripe form encoding
        $payload = [
            'mode' => 'subscription',
            'customer' => $customerId,
            'success_url' => url('/billing/success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/pricing') . '?canceled=1',
            'client_reference_id' => (string) $user->id,
            'metadata[user_id]' => (string) $user->id,
            'metadata[plan_id]' => $plan,
            'subscription_data[metadata][user_id]' => (string) $user->id,
            'subscription_data[metadata][plan_id]' => $plan,
        ];

        if ($priceId) {
            $payload['line_items[0][price]'] = $priceId;
            $payload['line_items[0][quantity]'] = 1;
        } else {
            $payload['line_items[0][price_data][currency]'] = config('stripe.currency', 'usd');
            $payload['line_items[0][price_data][unit_amount]'] = $planMeta['amount'];
            $payload['line_items[0][price_data][recurring][interval]'] = 'month';
            $payload['line_items[0][price_data][product_data][name]'] = 'CurrentSign ' . $planMeta['name'];
            $payload['line_items[0][price_data][product_data][metadata][plan_id]'] = $plan;
            $payload['line_items[0][quantity]'] = 1;
        }

        try {
            $session = $this->stripe->post('/checkout/sessions', $payload);
        } catch (\Throwable $e) {
            Log::error('Stripe checkout failed', ['error' => $e->getMessage()]);

            return redirect('/pricing')->with('error', $e->getMessage());
        }

        if (empty($session['url'])) {
            return redirect('/pricing')->with('error', 'Unable to start Stripe Checkout.');
        }

        return redirect()->away($session['url']);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (! $sessionId || ! Auth::check()) {
            return redirect('/user/dashboard');
        }

        try {
            $session = $this->stripe->get('/checkout/sessions/' . $sessionId, [
                'expand[]' => 'subscription',
            ]);
            $userId = (int) ($session['client_reference_id'] ?? Auth::id());
            $planId = $session['metadata']['plan_id']
                ?? ($session['subscription']['metadata']['plan_id'] ?? null);
            $customerId = is_string($session['customer'] ?? null) ? $session['customer'] : ($session['customer']['id'] ?? null);
            $subscription = $session['subscription'] ?? null;
            $subscriptionId = is_string($subscription) ? $subscription : ($subscription['id'] ?? null);
            $periodEnd = is_array($subscription) ? ($subscription['current_period_end'] ?? null) : null;

            if ($planId && $userId) {
                $this->entitlements->activatePlan($userId, $planId, $customerId, $subscriptionId, $periodEnd);
            }
        } catch (\Throwable $e) {
            Log::warning('Stripe success sync failed', ['error' => $e->getMessage()]);
        }

        $pendingService = app(\App\Services\PendingSignatureSend::class);
        $pending = $pendingService->get();
        $returnUrl = session('billing_return_url')
            ?: ($pending['return_url'] ?? url('/user/dashboard'));

        // Do not auto-send. Bring user back to the send form with saved doc/email.
        if ($pending && (int) ($pending['user_id'] ?? 0) === (int) Auth::id()) {
            return redirect()->to($returnUrl)
                ->with('success', 'Subscription activated. Review your document and email, then submit.')
                ->with('reopen_send_modal', true);
        }

        session()->forget('billing_return_url');

        return redirect()->to($returnUrl)
            ->with('success', 'Subscription activated. You can continue where you left off.');
    }

    public function portal(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $customerId = $user->stripe_customer_id ?? null;
        if (! $customerId) {
            return redirect('/pricing')->with('error', 'No Stripe customer found. Subscribe to a plan first.');
        }

        try {
            $portal = $this->stripe->post('/billing_portal/sessions', [
                'customer' => $customerId,
                'return_url' => url('/user/dashboard'),
            ]);
        } catch (\Throwable $e) {
            return redirect('/user/dashboard')->with('error', $e->getMessage());
        }

        return redirect()->away($portal['url']);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');
        $secret = config('stripe.webhook_secret');

        if ($secret) {
            try {
                $this->verifySignature($payload, $sig, $secret);
            } catch (\Throwable $e) {
                Log::warning('Stripe webhook signature failed', ['error' => $e->getMessage()]);

                return response('Invalid signature', 400);
            }
        }

        $event = json_decode($payload, true);
        if (! is_array($event)) {
            return response('Invalid payload', 400);
        }

        $type = $event['type'] ?? '';
        $object = $event['data']['object'] ?? [];

        try {
            match ($type) {
                'checkout.session.completed' => $this->onCheckoutCompleted($object),
                'customer.subscription.updated',
                'customer.subscription.created' => $this->onSubscriptionUpdated($object),
                'customer.subscription.deleted' => $this->onSubscriptionDeleted($object),
                'invoice.paid' => $this->onInvoicePaid($object),
                'invoice.payment_failed' => $this->onInvoiceFailed($object),
                default => null,
            };
        } catch (\Throwable $e) {
            Log::error('Stripe webhook handler failed', ['type' => $type, 'error' => $e->getMessage()]);

            return response('Handler error', 500);
        }

        return response('ok', 200);
    }

    private function onCheckoutCompleted(array $session): void
    {
        $userId = (int) ($session['client_reference_id'] ?? ($session['metadata']['user_id'] ?? 0));
        $planId = $session['metadata']['plan_id'] ?? null;
        $customerId = is_string($session['customer'] ?? null) ? $session['customer'] : null;
        $subscriptionId = is_string($session['subscription'] ?? null) ? $session['subscription'] : null;

        if ($userId && $planId) {
            $this->entitlements->activatePlan($userId, $planId, $customerId, $subscriptionId, null);
        }
    }

    private function onSubscriptionUpdated(array $subscription): void
    {
        $userId = (int) ($subscription['metadata']['user_id'] ?? 0);
        if (! $userId && ! empty($subscription['customer'])) {
            $userId = (int) (DB::table('users')
                ->where('stripe_customer_id', $subscription['customer'])
                ->value('id') ?? 0);
        }
        if (! $userId) {
            return;
        }

        $planId = $subscription['metadata']['plan_id']
            ?? $this->planFromAmount($subscription)
            ?? (DB::table('users')->where('id', $userId)->value('plan'));

        $status = $subscription['status'] ?? 'active';
        if (in_array($status, ['active', 'trialing'], true) && $planId) {
            $this->entitlements->activatePlan(
                $userId,
                $planId,
                $subscription['customer'] ?? null,
                $subscription['id'] ?? null,
                $subscription['current_period_end'] ?? null
            );
        } elseif ($status === 'past_due') {
            $this->entitlements->markPastDue($userId);
        } elseif (in_array($status, ['canceled', 'unpaid', 'incomplete_expired'], true)) {
            $this->entitlements->markCanceled($userId);
        }
    }

    private function onSubscriptionDeleted(array $subscription): void
    {
        $userId = (int) ($subscription['metadata']['user_id'] ?? 0);
        if (! $userId && ! empty($subscription['customer'])) {
            $userId = (int) (DB::table('users')
                ->where('stripe_customer_id', $subscription['customer'])
                ->value('id') ?? 0);
        }
        if ($userId) {
            $this->entitlements->markCanceled($userId);
        }
    }

    private function onInvoicePaid(array $invoice): void
    {
        $customerId = $invoice['customer'] ?? null;
        if (! $customerId) {
            return;
        }
        $user = DB::table('users')->where('stripe_customer_id', $customerId)->first();
        if (! $user) {
            return;
        }

        $periodEnd = $invoice['lines']['data'][0]['period']['end'] ?? null;
        $update = [
            'subscription_status' => 'active',
            'sends_this_period' => 0,
            'period_start' => now(),
        ];
        if ($periodEnd && Schema::hasColumn('users', 'current_period_end')) {
            $update['current_period_end'] = date('Y-m-d H:i:s', (int) $periodEnd);
        }
        DB::table('users')->where('id', $user->id)->update($update);
    }

    private function onInvoiceFailed(array $invoice): void
    {
        $customerId = $invoice['customer'] ?? null;
        if (! $customerId) {
            return;
        }
        $userId = DB::table('users')->where('stripe_customer_id', $customerId)->value('id');
        if ($userId) {
            $this->entitlements->markPastDue((int) $userId);
        }
    }

    private function planFromAmount(array $subscription): ?string
    {
        $amount = $subscription['items']['data'][0]['price']['unit_amount'] ?? null;
        foreach (config('stripe.plans') as $id => $plan) {
            if ((int) $plan['amount'] === (int) $amount) {
                return $id;
            }
        }

        return null;
    }

    private function verifySignature(string $payload, ?string $header, string $secret): void
    {
        if (! $header) {
            throw new \RuntimeException('Missing Stripe-Signature header');
        }
        $parts = [];
        foreach (explode(',', $header) as $item) {
            [$k, $v] = array_pad(explode('=', trim($item), 2), 2, null);
            if ($k && $v) {
                $parts[$k][] = $v;
            }
        }
        $timestamp = $parts['t'][0] ?? null;
        $signatures = $parts['v1'] ?? [];
        if (! $timestamp || empty($signatures)) {
            throw new \RuntimeException('Malformed Stripe-Signature header');
        }
        if (abs(time() - (int) $timestamp) > 300) {
            throw new \RuntimeException('Stripe webhook timestamp too old');
        }
        $signed = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
        foreach ($signatures as $sig) {
            if (hash_equals($signed, $sig)) {
                return;
            }
        }
        throw new \RuntimeException('Stripe signature mismatch');
    }
}
