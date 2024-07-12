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
        //عمليات الاستلام من الجمعية
        Schema::create('receipt_from_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_to_factory_id')->references('id')->on('transfer_to_factories');
            $table->foreignId('association_id')->references('id')->on('users');
            $table->foreignId('driver_id')->references('id')->on('drivers');
            $table->foreignId('factory_id')->references('id')->on('factories');
            $table->dateTime('start_time_of_collection');
            $table->dateTime('end_time_of_collection');
            $table->integer('number_of_packages');
            $table->enum('package_cleanliness', ['clean', 'somewhat_clean', 'not_clean'])->default('clean'); // نظافة العبوات
            $table->enum('transport_cleanliness', ['clean', 'somewhat_clean', 'not_clean'])->default('clean'); // نظافة وسيلة النقل
            $table->enum('driver_personal_hygiene', ['clean', 'somewhat_clean', 'not_clean'])->default('clean'); // النظافة الشخصية للسائق
            $table->enum('ac_operation', ['on', 'off', 'not_available'])->default('on'); // تشغيل التكييف
            $table->decimal('defective_quantity_due_to_coagulation', 8, 2);
            $table->decimal('defective_quantity_due_to_impurities', 8, 2);
            $table->decimal('defective_quantity_due_to_density', 8, 2);
            $table->decimal('defective_quantity_due_to_acidity', 8, 2);
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_from_associations');
    }
};
