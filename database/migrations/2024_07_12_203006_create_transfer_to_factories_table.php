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
        //عمليات التحويل الى المصنع
        Schema::create('transfer_to_factories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('association_id')->references('id')->on('users');
            $table->foreignId('driver_id')->references('id')->on('drivers');
            $table->foreignId('factory_id')->references('id')->on('factories');
            $table->string('means_of_transportation');
            $table->decimal('quantity', 8, 2); 
            $table->dateTime('date_and_time');
            $table->boolean('status')->default(0);
            $table->text('notes'); // ملاحظات
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_to_factories');
    }
};
