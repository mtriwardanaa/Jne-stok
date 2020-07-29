<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaToBarangMasukDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_masuk_detail', function (Blueprint $table) {
            $table->string('harga_barang')->after('qty_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_barang_masuk_detail', function (Blueprint $table) {
            $table->dropColumn('harga_barang');
        });
    }
}
