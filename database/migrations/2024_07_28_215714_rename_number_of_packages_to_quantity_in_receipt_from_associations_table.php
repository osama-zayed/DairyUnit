<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNumberOfPackagesToQuantityInReceiptFromAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('receipt_from_associations', function (Blueprint $table) {
            $table->renameColumn('number_of_packages', 'quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_from_associations', function (Blueprint $table) {
            $table->renameColumn('quantity', 'number_of_packages');
        });
    }
}
