<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferencesToBarangKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_keluar', function (Blueprint $table) {
            $table->bigInteger('id_order')->unsigned()->nullable()->after('id');

            $table->foreign('id_order')->references('id')->on('stok_order')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('stok_barang_keluar_id_order_foreign');
            $table->dropIndex('stok_barang_keluar_id_order_foreign');
            $table->dropColumn('id_order');
        });
    }
}
