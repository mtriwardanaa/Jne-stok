<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_barang_keluar')->unsigned();
            $table->string('no_invoice');
            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('tanggal_invoice');
            $table->string('status')->default('unpaid');
            $table->timestamps();

            $table->foreign('id_barang_keluar')->references('id')->on('stok_barang_keluar')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_invoice');
    }
}
