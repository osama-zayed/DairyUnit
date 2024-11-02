<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCattleFieldsToFamiliesTable extends Migration
{
    public function up()
    {
        Schema::table('families', function (Blueprint $table) {
            // إضافة الحقول المطلوبة
            $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
            $table->foreignId('directorate_id')->constrained()->onDelete('cascade');
            $table->foreignId('isolation_id')->constrained()->onDelete('cascade');
            $table->foreignId('village_id')->constrained()->onDelete('cascade');
            $table->integer('local_cows_producing')->default(0);
            $table->integer('local_cows_non_producing')->default(0);
            $table->integer('born_cows_producing')->default(0);
            $table->integer('born_cows_non_producing')->default(0);
            $table->integer('imported_cows_producing')->default(0);
            $table->integer('imported_cows_non_producing')->default(0);
        });
    }

    public function down()
    {
        Schema::table('families', function (Blueprint $table) {
            // حذف الحقول عند التراجع عن الهجرة
            $table->dropForeign(['governorate_id']);
            $table->dropForeign(['directorate_id']);
            $table->dropForeign(['isolation_id']);
            $table->dropForeign(['village_id']);
            $table->dropColumn([
                'governorate_id',
                'directorate_id',
                'isolation_id',
                'village_id',
                'local_cows_producing',
                'local_cows_non_producing',
                'born_cows_producing',
                'born_cows_non_producing',
                'imported_cows_producing',
                'imported_cows_non_producing',
            ]);
        });
    }
}