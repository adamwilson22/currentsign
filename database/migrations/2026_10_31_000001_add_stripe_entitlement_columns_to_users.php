<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->index();
            }
            if (! Schema::hasColumn('users', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->index();
            }
            if (! Schema::hasColumn('users', 'sends_this_period')) {
                $table->unsignedInteger('sends_this_period')->default(0);
            }
            if (! Schema::hasColumn('users', 'period_start')) {
                $table->timestamp('period_start')->nullable();
            }
            if (! Schema::hasColumn('users', 'current_period_end')) {
                $table->timestamp('current_period_end')->nullable();
            }
            if (! Schema::hasColumn('users', 'plan')) {
                $table->string('plan')->nullable()->default('trial');
            }
            if (! Schema::hasColumn('users', 'subscription_status')) {
                $table->string('subscription_status')->nullable()->default('trialing');
            }
            if (! Schema::hasColumn('users', 'docs_per_month')) {
                $table->unsignedInteger('docs_per_month')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'stripe_customer_id',
                'stripe_subscription_id',
                'sends_this_period',
                'period_start',
                'current_period_end',
            ] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
