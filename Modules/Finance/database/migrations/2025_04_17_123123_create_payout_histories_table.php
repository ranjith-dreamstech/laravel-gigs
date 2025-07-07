<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payout_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->nullable()->comment('1 = provider , 2 = user');
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('reference_id')->nullable();
            $table->integer('total_bookings');
            $table->decimal('total_earnings', 10, 2);
            $table->decimal('admin_earnings', 10, 2)->nullable();
            $table->decimal('pay_due', 10, 2);
            $table->decimal('process_amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->string('payment_proof')->nullable();
            $table->integer('created_by')->default(1);
            $table->timestamps();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_histories');
    }
};
