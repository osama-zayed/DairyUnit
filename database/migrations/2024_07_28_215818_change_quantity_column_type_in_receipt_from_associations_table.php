<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeQuantityColumnTypeInReceiptFromAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `receipt_from_associations` CHANGE `quantity` `quantity` DECIMAL(11, 2) NOT NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `receipt_from_associations` CHANGE `quantity` `quantity` INTEGER NOT NULL;');
    }
}
