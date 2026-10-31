<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EntitlementService;
use App\Services\StripeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $row = DB::table('users')->where('id', $user->id)->first() ?: $user;
        $snap = app(EntitlementService::class)->entitlementSnapshot($row);

        $sub = null;
        if (Schema::hasTable('subscriptions')) {
            $sub = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->first();
        }

        return $this->sendResponse(array_merge($snap, [
            'source' => $sub->source ?? ($snap['is_trial'] ? 'trial' : 'unknown'),
            'cancel_at_period_end' => (bool) ($sub->cancel_at_period_end ?? false),
            'current_period_end' => $sub->current_period_end ?? $snap['current_period_end'],
            'upgrade_url' => url('/pricing'),
        ]), 'Entitlement fetched.');
    }

    /**
     * Mobile: create a Stripe Checkout Session (Bearer auth) and return the hosted URL.
     * Avoids opening /billing/checkout in an unauthenticated browser tab (404 / login loop).
     */
    public function createCheckout(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|string|in:basic,standard,premium',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors()->first(), [], [], 422);
        }

        $plan = strtolower((string) $request->input('plan_id'));
        $plans = config('stripe.plans');
        $planMeta = $plans[$plan] ?? null;
        if (! $planMeta) {
            return $this->sendError(null, 'Unknown plan.', [], [], 422);
        }

        $user = Auth::guard('api')->user();
        $stripe = app(StripeClient::class);

        try {
            $customerId = $user->stripe_customer_id ?? null;
            if (! $customerId) {
                $customer = $stripe->post('/customers', [
                    'email' => $user->email,
                    'name' => $user->full_name ?? $user->name ?? '',
                    'metadata[user_id]' => (string) $user->id,
                ]);
                $customerId = $customer['id'];
                if (Schema::hasColumn('users', 'stripe_customer_id')) {
                    DB::table('users')->where('id', $user->id)->update(['stripe_customer_id' => $customerId]);
                }
            }

            $priceId = config('stripe.prices.' . $plan);
            $successUrl = url('/billing/success') . '?session_id={CHECKOUT_SESSION_ID}&mobile=1';
            $cancelUrl = url('/pricing') . '?canceled=1&mobile=1';

            $payload = [
                'mode' => 'subscription',
                'customer' => $customerId,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
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

            $session = $stripe->post('/checkout/sessions', $payload);
            if (empty($session['url']) || empty($session['id'])) {
                return $this->sendError(null, 'Unable to start Stripe Checkout.', [], [], 500);
            }

            return $this->sendResponse([
                'checkout_url' => $session['url'],
                'session_id' => $session['id'],
                'plan_id' => $plan,
            ], 'Checkout session created.');
        } catch (\Throwable $e) {
            Log::error('API billing checkout failed', ['error' => $e->getMessage()]);

            return $this->sendError(null, $e->getMessage(), [], [], 500);
        }
    }

    /** After mobile Stripe Checkout success — sync entitlement from session_id. */
    public function completeCheckout(Request $request)
    {
        if (! Auth::guard('api')->check()) {
            return $this->sendError(null, 'Unauthorized.', [], [], 401);
        }

        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError(null, $validator->errors()->first(), [], [], 422);
        }

        $user = Auth::guard('api')->user();
        $stripe = app(StripeClient::class);

        try {
            $session = $stripe->get('/checkout/sessions/' . $request->input('session_id'), [
                'expand[]' => 'subscription',
            ]);

            if (($session['payment_status'] ?? '') !== 'paid' && ($session['status'] ?? '') !== 'complete') {
                return $this->sendError(null, 'Payment not completed yet.', [], [], 402);
            }

            $refUser = (int) ($session['client_reference_id'] ?? 0);
            if ($refUser && $refUser !== (int) $user->id) {
                return $this->sendError(null, 'Session does not belong to this account.', [], [], 403);
            }

            $planId = $session['metadata']['plan_id']
                ?? ($session['subscription']['metadata']['plan_id'] ?? null);
            $customerId = is_string($session['customer'] ?? null)
                ? $session['customer']
                : ($session['customer']['id'] ?? null);
            $subscription = $session['subscription'] ?? null;
            $subscriptionId = is_string($subscription) ? $subscription : ($subscription['id'] ?? null);
            $periodEnd = is_array($subscription) ? ($subscription['current_period_end'] ?? null) : null;

            if (! $planId) {
                return $this->sendError(null, 'Plan missing on checkout session.', [], [], 422);
            }

            app(EntitlementService::class)->activatePlan(
                (int) $user->id,
                $planId,
                $customerId,
                $subscriptionId,
                $periodEnd ? (string) $periodEnd : null,
                'stripe',
                $planId
            );

            return $this->entitlement($request);
        } catch (\Throwable $e) {
            Log::error('API billing complete failed', ['error' => $e->getMessage()]);

            return $this->sendError(null, $e->getMessage(), [], [], 500);
        }
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

        app(EntitlementService::class)->activatePlan(
            $userId,
            $planId,
            null,
            $txnId,
            (string) $periodEnd->getTimestamp(),
            $source,
            $productId
        );

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
