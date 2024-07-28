<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDefectiveQuantitiesFromReceiptFromAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('receipt_from_associations', function (Blueprint $table) {
            $table->dropColumn([
                'defective_quantity_due_to_coagulation',
                'defective_quantity_due_to_impurities',
                'defective_quantity_due_to_density',
                'defective_quantity_due_to_acidity',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_from_associations', function (Blueprint $table) {
            $table->decimal('defective_quantity_due_to_coagulation', 8, 2);
            $table->decimal('defective_quantity_due_to_impurities', 8, 2);
            $table->decimal('defective_quantity_due_to_density', 8, 2);
            $table->decimal('defective_quantity_due_to_acidity', 8, 2);
        });
    }
}
