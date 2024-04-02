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
                $table->unsignedBigInteger('customer_id')->index()->foreign('customer_id')->references('id')->on('customers');
                $table->unsignedBigInteger('responsible_id')->index()->foreign('user_id')->references('id')->on('users');
                $table->string('name', 255)->nullable();
                $table->enum('status', ['missing', 'gain', 'running', 'pending'])->default('pending')->nullable();
                $table->decimal('valuation', $precision = 10, $scale = 2)->nullable();
                $table->boolean('new')->default(true);
                $table->boolean('active')->default(true);
                $table->dateTime('closing_forecast', $precision = 0)->nullable();
                $table->dateTime('closing_date', $precision = 0)->nullable();
                $table->timestamps();
                $table->softDeletes();
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
