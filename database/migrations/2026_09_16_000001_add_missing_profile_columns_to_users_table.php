<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('name');
            }
            if (! Schema::hasColumn('users', 'dob')) {
                $table->string('dob', 32)->nullable();
            }
            if (! Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 16)->nullable();
            }
            if (! Schema::hasColumn('users', 'mobile_number')) {
                $table->string('mobile_number', 32)->nullable();
            }
            if (! Schema::hasColumn('users', 'image')) {
                $table->string('image')->nullable();
            }
            if (! Schema::hasColumn('users', 'otp')) {
                $table->string('otp', 16)->nullable();
            }
            if (! Schema::hasColumn('users', 'otp_verify')) {
                $table->string('otp_verify', 16)->nullable();
            }
            if (! Schema::hasColumn('users', 'otp_time')) {
                $table->string('otp_time', 32)->nullable();
            }
            if (! Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 32)->nullable();
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable();
            }
            if (! Schema::hasColumn('users', 'is_trial')) {
                $table->string('is_trial', 16)->default('true');
            }
            if (! Schema::hasColumn('users', 'plan')) {
                $table->string('plan', 64)->nullable();
            }
            if (! Schema::hasColumn('users', 'subscription_status')) {
                $table->string('subscription_status', 64)->nullable();
            }
            if (! Schema::hasColumn('users', 'docs_per_month')) {
                $table->integer('docs_per_month')->nullable();
            }
            if (! Schema::hasColumn('users', 'notification')) {
                $table->string('notification', 16)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [
                'full_name', 'dob', 'country_code', 'mobile_number', 'image',
                'otp', 'otp_verify', 'otp_time', 'gender', 'address',
                'is_trial', 'plan', 'subscription_status', 'docs_per_month', 'notification',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
