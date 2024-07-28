<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQuantityTypeInReceiptFromAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('receipt_from_associations', function (Blueprint $table) {
            // تغيير نوع البيانات للعمود
            $table->decimal('quantity', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipt_from_associations', function (Blueprint $table) {
            // إعادة نوع البيانات الأصلي
            $table->integer('quantity')->change();
        });
    }
}
