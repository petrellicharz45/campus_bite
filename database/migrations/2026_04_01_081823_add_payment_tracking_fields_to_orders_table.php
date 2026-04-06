<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('payment_status');
            $table->string('payment_provider_reference')->nullable()->after('payment_reference');
            $table->string('payment_channel')->nullable()->after('payment_provider_reference');
            $table->timestamp('paid_at')->nullable()->after('placed_at');
            $table->json('payment_meta')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_reference',
                'payment_provider_reference',
                'payment_channel',
                'paid_at',
                'payment_meta',
            ]);
        });
    }
};
