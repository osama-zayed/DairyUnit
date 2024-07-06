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
        //معلومات تجميع الحليب 
        Schema::create('collecting_milk_from_families', function (Blueprint $table) {
            $table->id();
            $table->dateTime('collection_date_and_time');
            $table->decimal('quantity', 8, 2); 
            $table->foreignId('association_id')->references('id')->on('users');
            $table->foreignId('family_id')->references('id')->on('families');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->text('nots');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collecting_milk_from_captivities');
    }
};
