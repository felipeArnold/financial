<?php

use App\Models\Custumer;
use App\Models\Person;
use App\Models\User;
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
//            $table->foreignIdFor(Custumer::class);
            $table->string('order_number', 15)->nullable();
            $table->foreignIdFor(Person::class);
            $table->foreignIdFor(User::class);
            $table->enum('type', ['service', 'sale']);
            $table->enum('status', ['budget', 'open', 'progress', 'finished', 'canceled', 'waiting', 'approved'])->default('budget');
            $table->date('initial_date')->nullable();
            $table->date('final_date')->nullable();
            $table->text('description')->nullable();
            $table->text('observation')->nullable();
            $table->text('note')->nullable();
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
