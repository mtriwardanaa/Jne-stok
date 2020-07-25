<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_order_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_order')->unsigned();
            $table->bigInteger('id_barang')->unsigned();
            $table->integer('qty_barang');
            $table->timestamps();

            $table->foreign('id_order')->references('id')->on('stok_order')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_barang')->references('id')->on('stok_barang')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_order_detail');
    }
}
