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
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('general_price', 10, 2);
            $table->unsignedInteger('days');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('no_revisions');
            $table->text('tags');
            $table->text('description');
            $table->string('fast_service_tile')->nullable();
            $table->decimal('fast_service_price', 10, 2)->nullable();
            $table->unsignedInteger('fast_service_days')->nullable();
            $table->unsignedInteger('fast_dis')->nullable();
            $table->enum('buyer', ['buyer', 'remote']);
            $table->longText('faqs');
            $table->longText('extra_service');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};
