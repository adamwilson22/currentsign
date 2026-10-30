<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EntitlementService
{
    public function trialLimit(): int
    {
        return (int) config('stripe.trial_sends', 5);
    }

    public function canSend(object $user, int $quantity = 1): array
    {
        $quantity = max(1, $quantity);
        $this->maybeResetPeriod($user);

        $status = strtolower((string) ($user->subscription_status ?? ''));
        $plan = strtolower((string) ($user->plan ?? 'trial'));
        $isTrial = ($user->is_trial ?? 'true') !== 'false';

        // Active paid plan
        if (in_array($status, ['active', 'trialing'], true) && in_array($plan, ['basic', 'standard', 'premium'], true)) {
            $docsPerMonth = $user->docs_per_month;
            if ($docsPerMonth === null || $plan === 'premium') {
                return ['ok' => true, 'reason' => null, 'code' => 'paid_unlimited'];
            }
            $used = (int) ($user->sends_this_period ?? 0);
            if ($used + $quantity <= (int) $docsPerMonth) {
                return ['ok' => true, 'reason' => null, 'code' => 'paid_quota'];
            }

            return [
                'ok' => false,
                'reason' => 'Monthly document limit reached. Upgrade your plan or wait for the next billing period.',
                'code' => 'quota_exhausted',
            ];
        }

        // Free trial: first N lifetime signature sends
        if ($isTrial) {
            $used = $this->lifetimeSendCount((int) $user->id);
            if ($used + $quantity <= $this->trialLimit()) {
                return ['ok' => true, 'reason' => null, 'code' => 'trial'];
            }

            if ($used >= $this->trialLimit()) {
                $this->endTrial((int) $user->id);
            }

            return [
                'ok' => false,
                'reason' => 'Trial ended. Please choose a plan to continue sending documents.',
                'code' => 'trial_ended',
            ];
        }

        return [
            'ok' => false,
            'reason' => 'Trial ended. Please choose a plan to continue sending documents.',
            'code' => 'upgrade_required',
        ];
    }

    public function recordSend(object $user): void
    {
        $this->maybeResetPeriod($user);

        $status = strtolower((string) ($user->subscription_status ?? ''));
        $plan = strtolower((string) ($user->plan ?? 'trial'));
        $isPaid = in_array($status, ['active', 'trialing'], true)
            && in_array($plan, ['basic', 'standard', 'premium'], true);

        if ($isPaid && Schema::hasColumn('users', 'sends_this_period')) {
            DB::table('users')->where('id', $user->id)->increment('sends_this_period');
        }

        $used = $this->lifetimeSendCount((int) $user->id);
        // After insert, count includes the new row; caller should record after insert.
        if (($user->is_trial ?? 'true') !== 'false' && $used >= $this->trialLimit()) {
            $this->endTrial((int) $user->id);
        }
    }

    public function recordSendAfterInsert(int $userId, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        $user = DB::table('users')->where('id', $userId)->first();
        if (! $user) {
            return;
        }

        $this->maybeResetPeriod($user);

        $status = strtolower((string) ($user->subscription_status ?? ''));
        $plan = strtolower((string) ($user->plan ?? 'trial'));
        $isPaid = in_array($status, ['active', 'trialing'], true)
            && in_array($plan, ['basic', 'standard', 'premium'], true);

        if ($isPaid && Schema::hasColumn('users', 'sends_this_period')) {
            DB::table('users')->where('id', $userId)->increment('sends_this_period', $quantity);
        }

        $used = $this->lifetimeSendCount($userId);
        if (($user->is_trial ?? 'true') !== 'false' && $used >= $this->trialLimit()) {
            $this->endTrial($userId);
        }
    }

    public function activatePlan(
        int $userId,
        string $planId,
        ?string $customerId,
        ?string $subscriptionId,
        ?string $periodEnd = null,
        string $source = 'stripe',
        ?string $productId = null
    ): void {
        $plans = config('stripe.plans');
        $plan = $plans[$planId] ?? null;
        if (! $plan) {
            throw new \InvalidArgumentException('Unknown plan: ' . $planId);
        }

        $update = [
            'is_trial' => 'false',
            'plan' => $planId,
            'subscription_status' => 'active',
            'docs_per_month' => $plan['docs_per_month'],
        ];

        if (Schema::hasColumn('users', 'stripe_customer_id') && $customerId) {
            $update['stripe_customer_id'] = $customerId;
        }
        if (Schema::hasColumn('users', 'stripe_subscription_id') && $subscriptionId && $source === 'stripe') {
            $update['stripe_subscription_id'] = $subscriptionId;
        }
        if (Schema::hasColumn('users', 'sends_this_period')) {
            $update['sends_this_period'] = 0;
        }
        if (Schema::hasColumn('users', 'period_start')) {
            $update['period_start'] = now();
        }
        if (Schema::hasColumn('users', 'current_period_end') && $periodEnd) {
            $update['current_period_end'] = date('Y-m-d H:i:s', is_numeric($periodEnd) ? (int) $periodEnd : strtotime($periodEnd));
        } elseif (Schema::hasColumn('users', 'current_period_end')) {
            $update['current_period_end'] = now()->addMonth();
        }

        DB::table('users')->where('id', $userId)->update($update);

        if (Schema::hasTable('subscriptions')) {
            DB::table('subscriptions')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'store_transaction_id' => $subscriptionId ?: ($source . '_' . $userId),
                ],
                [
                    'plan_id' => $planId,
                    'product_id' => $productId ?: $planId,
                    'status' => 'active',
                    'source' => $source,
                    'current_period_end' => $update['current_period_end'] ?? now()->addMonth(),
                    'cancel_at_period_end' => false,
                    'meta' => json_encode(['customer' => $customerId]),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function entitlementSnapshot(object $user): array
    {
        $gate = $this->canSend($user);
        $plan = strtolower((string) ($user->plan ?? 'trial'));
        $isTrial = ($user->is_trial ?? 'true') !== 'false';
        $lifetime = $this->lifetimeSendCount((int) $user->id);
        $trialLimit = $this->trialLimit();
        $docsPerMonth = $user->docs_per_month ?? null;
        $usedPeriod = (int) ($user->sends_this_period ?? 0);

        $remaining = null;
        if ($isTrial && ! in_array($plan, ['basic', 'standard', 'premium'], true)) {
            $remaining = max(0, $trialLimit - $lifetime);
        } elseif ($docsPerMonth !== null && $plan !== 'premium') {
            $remaining = max(0, (int) $docsPerMonth - $usedPeriod);
        }

        return [
            'plan' => $plan,
            'status' => $user->subscription_status ?? ($isTrial ? 'trialing' : 'expired'),
            'is_trial' => $isTrial,
            'docs_per_month' => $docsPerMonth,
            'sends_this_period' => $usedPeriod,
            'lifetime_sends' => $lifetime,
            'trial_limit' => $trialLimit,
            'trial_remaining' => max(0, $trialLimit - $lifetime),
            'sends_remaining' => $remaining,
            'can_send' => $gate['ok'],
            'gate_code' => $gate['code'],
            'gate_reason' => $gate['reason'],
            'current_period_end' => $user->current_period_end ?? null,
        ];
    }

    public function markCanceled(int $userId): void
    {
        DB::table('users')->where('id', $userId)->update([
            'subscription_status' => 'canceled',
            'plan' => 'expired_trial',
            'docs_per_month' => 0,
        ]);
    }

    public function markPastDue(int $userId): void
    {
        DB::table('users')->where('id', $userId)->update([
            'subscription_status' => 'past_due',
        ]);
    }

    private function lifetimeSendCount(int $userId): int
    {
        if (! Schema::hasTable('signatures')) {
            return 0;
        }

        return (int) DB::table('signatures')->where('user_id', $userId)->count();
    }

    private function endTrial(int $userId): void
    {
        DB::table('users')->where('id', $userId)->update([
            'is_trial' => 'false',
            'plan' => 'expired_trial',
            'subscription_status' => 'expired',
        ]);
    }

    private function maybeResetPeriod(object $user): void
    {
        if (! Schema::hasColumn('users', 'current_period_end') || empty($user->current_period_end)) {
            return;
        }
        if (strtotime((string) $user->current_period_end) > time()) {
            return;
        }
        // Period ended without webhook — soft reset counter until webhook renews.
        if (Schema::hasColumn('users', 'sends_this_period')) {
            DB::table('users')->where('id', $user->id)->update([
                'sends_this_period' => 0,
                'period_start' => now(),
                'current_period_end' => now()->addMonth(),
            ]);
            $user->sends_this_period = 0;
        }
    }
}
