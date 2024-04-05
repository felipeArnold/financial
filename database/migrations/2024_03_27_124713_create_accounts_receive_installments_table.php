<?php

use App\Models\AccountsReceive;
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
        Schema::create('accounts_receive_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AccountsReceive::class);
            $table->integer('parcel')->default(1);
            $table->enum('type', ['credit_card', 'bank_slip', 'pix', 'transfer', 'deposit'])->default('bank_slip');
            $table->date('due_date');
            $table->date('pay_date')->nullable();
            $table->string('document_number', 20)->nullable();
            $table->decimal('value', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('interest', 10, 2)->default(0.00);
            $table->decimal('fine', 10, 2)->default(0.00);
            $table->decimal('value_paid', 10, 2)->default(0.00);
            $table->enum('status', ['open', 'paid', 'canceled'])->default('open');
            $table->text('observation')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_receive_installments');
    }
};
