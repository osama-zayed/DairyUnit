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
        //مخازن الجمعية
        Schema::create('assembly_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->references('id')->on('users');
            $table->decimal('quantity', 8, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assembly_stores');
    }
};
