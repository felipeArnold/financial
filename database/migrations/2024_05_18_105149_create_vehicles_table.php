<?php

use App\Models\Tenant;
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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('type', ['car', 'motorcycle', 'truck']);
            $table->foreignId('owner_id')->nullable()->constrained('people');
            $table->foreignId('model_id')->constrained('vehicles_models');
            $table->string('plate')->nullable();
            $table->integer('year')->nullable();
            $table->integer('mileage')->nullable();
            $table->decimal('price_sale', 10, 2)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('sale_date')->nullable();
            $table->date('purchase_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
