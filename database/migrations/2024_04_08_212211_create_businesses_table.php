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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('responsible_id')->index()->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('people_id')->foreign('people_id')->references('id')->on('people')->nullable();
            $table->unsignedBigInteger('stage_id')->foreign('stage_id')->references('id')->on('business_stages');
            $table->unsignedBigInteger('origin_id')->foreign('origin_id')->references('id')->on('business_origins')->nullable();
            $table->unsignedBigInteger('tag_id')->foreign('tag_id')->references('id')->on('business_tags')->nullable();
            $table->string('name', 255)->nullable();
            $table->enum('status', ['missing', 'gain', 'running', 'pending'])->default('pending')->nullable();
            $table->decimal('valuation', $precision = 10, $scale = 2)->nullable();
            $table->boolean('new')->default(true);
            $table->boolean('active')->default(true);
            $table->dateTime('closing_forecast', $precision = 0)->nullable();
            $table->dateTime('closing_date', $precision = 0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
