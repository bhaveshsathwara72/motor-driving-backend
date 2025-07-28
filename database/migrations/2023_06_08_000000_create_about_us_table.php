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
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->decimal('licence_price', 10, 2);
            $table->string('about_phone_no_1');
            $table->string('about_phone_no_2')->nullable();
            $table->text('address');
            $table->string('opening_hours');
            $table->string('about_image_1')->nullable();
            $table->string('about_image_2')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us');
    }
};