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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->foreignId('association_id')->nullable()->references('id')->on('associations');
            $table->foreignId('associations_branche_id')->nullable()->references('id')->on('associations_branches');
            $table->foreignId('factory_id')->nullable()->references('id')->on('factories');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
