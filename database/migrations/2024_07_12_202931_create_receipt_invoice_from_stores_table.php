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
        //عمليات التوريد من المجمعين
        Schema::create('receipt_invoice_from_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->references('id')->on('users');
            $table->foreignId('associations_branche_id')->nullable()->references('id')->on('users');
            $table->decimal('quantity', 8, 2); 
            $table->dateTime('date_and_time');
            $table->text('notes'); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_invoice_from_stores');
    }
};
