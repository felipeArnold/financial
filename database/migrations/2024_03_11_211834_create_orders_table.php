<?php

use App\Models\Custumer;
use App\Models\Person;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Custumer::class);
            $table->string('order_number', 15)->nullable();
            $table->foreignIdFor(Person::class);
            $table->enum('type', ['service', 'sale']);
            $table->enum('status', ['new', 'processing', 'shipped', 'delivered', 'cancelled']);
            $table->decimal('total', 10, 2);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
