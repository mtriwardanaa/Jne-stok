<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHargaInvoiceToHargaStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_harga', function (Blueprint $table) {
            $table->string('harga_barang_invoice')->default(0)->after('harga_barang');
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
            $table->dropColumn('harga_barang_invoice');
        });
    }
}
