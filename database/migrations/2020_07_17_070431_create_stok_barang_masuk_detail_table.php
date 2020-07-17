<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBarangMasukDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_barang_masuk_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_barang_masuk')->unsigned();
            $table->bigInteger('id_barang')->unsigned();
            $table->integer('qty_barang');
            $table->bigInteger('id_supplier')->unsigned();
            $table->timestamps();

            $table->foreign('id_barang_masuk')->references('id')->on('stok_barang_masuk')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_barang')->references('id')->on('stok_barang')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_supplier')->references('id')->on('stok_supplier')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_barang_masuk_detail');
    }
}
