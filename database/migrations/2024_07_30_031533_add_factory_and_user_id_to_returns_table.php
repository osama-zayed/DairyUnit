<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->unsignedBigInteger('factory_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn('factory_id');
            $table->dropColumn('user_id');
        });
    }
};
