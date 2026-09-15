<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('plan_id')->nullable(); // basic|standard|premium
                $table->string('product_id')->nullable(); // store product id
                $table->string('status')->default('trialing'); // trialing|active|grace|expired|cancelled
                $table->string('source')->nullable(); // iap_apple|iap_google|stripe|paypal_legacy|trial
                $table->string('store_transaction_id')->nullable()->index();
                $table->timestamp('current_period_end')->nullable();
                $table->boolean('cancel_at_period_end')->default(false);
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('plan_id')->nullable();
                $table->string('product_id')->nullable();
                $table->string('amount')->nullable();
                $table->string('currency', 8)->default('USD');
                $table->string('status')->default('paid'); // paid|refunded|failed|pending
                $table->string('source')->nullable();
                $table->string('store_transaction_id')->nullable()->index();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('social_accounts')) {
            Schema::create('social_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('provider'); // apple|google|facebook
                $table->string('provider_user_id');
                $table->string('email')->nullable();
                $table->timestamps();
                $table->unique(['provider', 'provider_user_id']);
            });
        }

        if (! Schema::hasTable('device_tokens')) {
            Schema::create('device_tokens', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('fcm_token');
                $table->string('platform')->nullable(); // ios|android
                $table->string('device_id')->nullable();
                $table->timestamps();
                $table->unique(['fcm_token']);
            });
        }

        Schema::table('users', function (Blueprint $table) {
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
        Schema::dropIfExists('device_tokens');
        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('subscriptions');
        Schema::table('users', function (Blueprint $table) {
            foreach (['plan', 'subscription_status', 'docs_per_month'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
