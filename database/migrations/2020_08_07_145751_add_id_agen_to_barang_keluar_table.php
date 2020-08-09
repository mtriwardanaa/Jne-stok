<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdAgenToBarangKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_keluar', function (Blueprint $table) {
            $table->bigInteger('id_agen')->unsigned()->nullable()->after('updated_by');
            $table->string('nama_user_request')->after('id_agen');

            $table->foreign('id_agen')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_barang_keluar', function (Blueprint $table) {
            $table->dropForeign('stok_barang_keluar_id_agen_foreign');
            $table->dropIndex('stok_barang_keluar_id_agen_foreign');
            $table->dropColumn('id_agen');
            $table->dropColumn('nama_user_request');
        });
    }
}
