<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaBarangToBarangKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_keluar_detail', function (Blueprint $table) {
            $table->string('harga_barang')->after('qty_barang')->nullable();
        });

        Schema::table('stok_barang_keluar', function (Blueprint $table) {
            $table->string('no_barang_keluar')->after('id');
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
            $table->dropColumn('no_barang_keluar');
        });

        Schema::table('stok_barang_keluar_detail', function (Blueprint $table) {
            $table->dropColumn('harga_barang');
        });
    }
}
