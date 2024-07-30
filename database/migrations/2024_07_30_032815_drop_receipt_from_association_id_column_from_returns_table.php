<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropReceiptFromAssociationIdColumnFromReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropForeign(['receipt_from_association_id']);
            $table->dropColumn('receipt_from_association_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->foreignId('receipt_from_association_id')->constrained()->onDelete('cascade');
        });
    }
}