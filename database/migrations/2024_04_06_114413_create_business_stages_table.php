<?php

use App\Models\BusinessFunnels;
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
        Schema::create('business_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(BusinessFunnels::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->integer('order');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_stages');
    }
};
