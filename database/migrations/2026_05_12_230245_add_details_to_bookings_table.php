<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_number')->unique()->nullable()->after('id');
            $table->integer('guests')->default(1)->after('phone');
            $table->decimal('total_price', 10, 2)->nullable()->after('end_date');
            $table->decimal('taxes', 10, 2)->nullable()->after('total_price');
            $table->decimal('deposit', 10, 2)->nullable()->after('taxes');
            $table->string('payment_status')->default('pending')->after('deposit'); // pending, paid, failed
            $table->string('payment_method')->nullable()->after('payment_status'); // on_site, card, momo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_number', 'guests', 'total_price', 'taxes', 'deposit', 'payment_status', 'payment_method']);
        });
    }
};
