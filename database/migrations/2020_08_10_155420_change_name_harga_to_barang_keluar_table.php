<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameHargaToBarangKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_keluar_detail', function (Blueprint $table) {
            $table->renameColumn('harga_barang', 'harga_barang_invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_barang_keluar_detail', function (Blueprint $table) {
            $table->renameColumn('harga_barang_invoice', 'harga_barang');
        });
    }
}
