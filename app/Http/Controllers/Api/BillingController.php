<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Validator;

class BillingController extends Controller
{
    /** Canonical plans used by web + mobile until Stripe Dashboard sync. */
    public static function planCatalog(): array
    {
        return [
            [
                'id' => 'basic',
                'name' => 'Basic',
                'price' => '15',
                'period' => '/month',
                'docs_per_month' => 10,
                'featured' => false,
                'store_product_id_ios' => 'currentsign_basic_monthly',
                'store_product_id_android' => 'currentsign_basic_monthly',
                'stripe_price_id' => null,
                'features' => [
                    '10 documents per month',
                    'Core e-signing functionality',
                    'Email notifications and automated reminders',
                    'Signature tracking',
                ],
            ],
            [
                'id' => 'standard',
                'name' => 'Standard',
                'price' => '49',
                'period' => '/month',
                'docs_per_month' => 50,
                'featured' => true,
                'store_product_id_ios' => 'currentsign_standard_monthly',
                'store_product_id_android' => 'currentsign_standard_monthly',
                'stripe_price_id' => null,
                'features' => [
                    '50 documents per month',
                    'All Basic features',
                    'Bulk-send capability (up to 20 recipients per batch)',
                    'Team collaboration (up to 3 users)',
                ],
            ],
            [
                'id' => 'premium',
                'name' => 'Premium',
                'price' => '99',
                'period' => '/month',
                'docs_per_month' => null,
                'featured' => false,
                'store_product_id_ios' => 'currentsign_premium_monthly',
                'store_product_id_android' => 'currentsign_premium_monthly',
                'stripe_price_id' => null,
                'features' => [
                    'Unlimited documents per month',
                    'All Standard features',
                    'Advanced templates and workflow automation',
                    'API access for custom integrations',
                    'Expanded team accounts (up to 10 users)',
                    'Priority support and dedicated account manager',
                ],
            ],
        ];
    }

    public function plans(Request $request)
    {
        return $this->sendResponse(self::planCatalog(), 'Plans fetched successfully.');
    }

    public function entitlement(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        $user = Auth::guard('api')->user();
        $sub = null;
        if (Schema::hasTable('subscriptions')) {
            $sub = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->first();
        }

        $isTrial = ($user->is_trial ?? 'true') !== 'false';
        $status = $sub->status ?? ($user->subscription_status ?? ($isTrial ? 'trialing' : 'expired'));
        $planId = $sub->plan_id ?? ($user->plan ?? ($isTrial ? 'trial' : 'expired_trial'));

        return $this->sendResponse([
            'plan' => $planId,
            'status' => $status,
            'is_trial' => $isTrial,
            'source' => $sub->source ?? 'trial',
            'current_period_end' => $sub->current_period_end ?? null,
            'cancel_at_period_end' => (bool) ($sub->cancel_at_period_end ?? false),
            'docs_per_month' => $user->docs_per_month ?? $this->docsForPlan($planId),
        ], 'Entitlement fetched.');
    }

    public function verifyIap(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        $validator = Validator::make($request->all(), [
            'platform' => 'required|in:ios,android',
            'product_id' => 'required|string',
            'verification_data' => 'required|string',
            'transaction_id' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors()->first(), [], [], 422);
        }

        $user = Auth::guard('api')->user();
        $planId = $this->planIdFromProduct($request->product_id);
        if (! $planId) {
            return $this->sendError(null, 'Unknown product_id.', [], [], 422);
        }

        $source = $request->platform === 'ios' ? 'iap_apple' : 'iap_google';
        $txnId = $request->transaction_id ?: ('local_' . time() . '_' . $user->id);

        // Production: verify JWS / Play purchase token with Apple/Google.
        // Sandbox / staging: accept client purchase after StoreKit/Play success.
        $this->activateSubscription($user->id, $planId, $request->product_id, $source, $txnId, [
            'verification_data_len' => strlen($request->verification_data),
            'platform' => $request->platform,
        ]);

        return $this->entitlement($request);
    }

    public function restoreIap(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        $user = Auth::guard('api')->user();
        $purchases = $request->input('purchases', []);
        if (! is_array($purchases) || empty($purchases)) {
            return $this->entitlement($request);
        }

        foreach ($purchases as $purchase) {
            $productId = $purchase['product_id'] ?? null;
            $planId = $this->planIdFromProduct($productId);
            if (! $planId) {
                continue;
            }
            $platform = $purchase['platform'] ?? 'ios';
            $source = $platform === 'android' ? 'iap_google' : 'iap_apple';
            $txnId = $purchase['transaction_id'] ?? ('restore_' . $user->id . '_' . $productId);
            $this->activateSubscription(
                $user->id,
                $planId,
                $productId,
                $source,
                $txnId,
                ['restored' => true]
            );
        }

        return $this->entitlement($request);
    }

    public function transactions(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        if (! Schema::hasTable('transactions')) {
            return $this->sendResponse([], 'No transactions.');
        }

        $rows = DB::table('transactions')
            ->where('user_id', Auth::guard('api')->id())
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return $this->sendResponse($rows, 'Transactions fetched.');
    }

    public function transactionDetail(Request $request, $id)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        $row = DB::table('transactions')
            ->where('user_id', Auth::guard('api')->id())
            ->where('id', $id)
            ->first();

        if (! $row) {
            return $this->sendError(null, 'Transaction not found.', [], [], 404);
        }

        return $this->sendResponse($row, 'Transaction fetched.');
    }

    public function stripeWebhook(Request $request)
    {
        // Placeholder: verify Stripe-Signature when STRIPE_WEBHOOK_SECRET is set.
        \Log::info('Stripe webhook received', ['type' => $request->input('type')]);
        return response()->json(['received' => true]);
    }

    public function appleWebhook(Request $request)
    {
        \Log::info('Apple ASN webhook received');
        return response()->json(['received' => true]);
    }

    public function googleWebhook(Request $request)
    {
        \Log::info('Google RTDN webhook received');
        return response()->json(['received' => true]);
    }

    private function activateSubscription(
        int $userId,
        string $planId,
        string $productId,
        string $source,
        string $txnId,
        array $meta = []
    ): void {
        $periodEnd = now()->addMonth();

        if (Schema::hasTable('subscriptions')) {
            DB::table('subscriptions')->updateOrInsert(
                ['user_id' => $userId, 'store_transaction_id' => $txnId],
                [
                    'plan_id' => $planId,
                    'product_id' => $productId,
                    'status' => 'active',
                    'source' => $source,
                    'current_period_end' => $periodEnd,
                    'cancel_at_period_end' => false,
                    'meta' => json_encode($meta),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        if (Schema::hasTable('transactions')) {
            $exists = DB::table('transactions')->where('store_transaction_id', $txnId)->exists();
            if (! $exists) {
                $catalog = collect(self::planCatalog())->firstWhere('id', $planId);
                DB::table('transactions')->insert([
                    'user_id' => $userId,
                    'plan_id' => $planId,
                    'product_id' => $productId,
                    'amount' => $catalog['price'] ?? '0',
                    'currency' => 'USD',
                    'status' => 'paid',
                    'source' => $source,
                    'store_transaction_id' => $txnId,
                    'meta' => json_encode($meta),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $update = [
            'is_trial' => 'false',
            'plan' => $planId,
            'subscription_status' => 'active',
            'docs_per_month' => $this->docsForPlan($planId),
        ];
        User::where('id', $userId)->update($update);
    }

    private function planIdFromProduct(?string $productId): ?string
    {
        if (! $productId) {
            return null;
        }
        foreach (self::planCatalog() as $plan) {
            if (
                $plan['store_product_id_ios'] === $productId
                || $plan['store_product_id_android'] === $productId
                || $plan['id'] === $productId
            ) {
                return $plan['id'];
            }
        }
        return null;
    }

    private function docsForPlan(string $planId): ?int
    {
        foreach (self::planCatalog() as $plan) {
            if ($plan['id'] === $planId) {
                return $plan['docs_per_month'];
            }
        }
        return 5;
    }
}
