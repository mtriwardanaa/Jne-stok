<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoHpUserRequestToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_order', function (Blueprint $table) {
            $table->string('hp_user_request')->nullable()->after('nama_user_request');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_order', function (Blueprint $table) {
            $table->dropColumn('hp_user_request');
        });
    }
}
