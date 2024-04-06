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
        Schema::create('business_origins', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Tenant::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('color')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_origins');
    }
};
