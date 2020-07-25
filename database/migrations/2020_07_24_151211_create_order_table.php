<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('tanggal');
            $table->dateTime('tanggal_approve')->nullable();
            $table->bigInteger('id_divisi')->unsigned();
            $table->bigInteger('id_kategori')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_divisi')->references('id')->on('divisi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_kategori')->references('id')->on('agen_kategori')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_order');
    }
}
