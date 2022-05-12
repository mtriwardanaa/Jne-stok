<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdRefMinBarangToStokBarangHargaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_harga', function (Blueprint $table) {
            $table->bigInteger('id_ref_min_barang')->unsigned()->nullable()->after('min_barang');

            $table->foreign('id_ref_min_barang')->references('id')->on('stok_barang_harga')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_barang_harga', function (Blueprint $table) {
            $table->dropForeign('stok_barang_harga_id_ref_min_barang_foreign');
            $table->dropIndex('stok_barang_harga_id_ref_min_barang_foreign');
            $table->dropColumn('id_ref_min_barang');
        });
    }
}
