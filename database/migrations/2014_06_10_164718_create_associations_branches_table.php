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
        // تفاصيل الفروع التابعة للجمعية.
        Schema::create('associations_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('association_id')->references('id')->on('associations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associations_branches');
    }
};
