<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistribusiSalesToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_keluar', function (Blueprint $table) {
            $table->integer('distribusi_sales')->default(0)->after('nama_user_request');
            $table->dateTime('tanggal_distribusi')->nullable()->after('distribusi_sales');
            $table->bigInteger('user_distribusi')->unsigned()->nullable()->after('tanggal_distribusi');

            $table->foreign('user_distribusi')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('stok_barang_keluar_user_distribusi_foreign');
            $table->dropIndex('stok_barang_keluar_user_distribusi_foreign');
            $table->dropColumn('user_distribusi');
            $table->dropColumn('distribusi_sales');
            $table->dropColumn('tanggal_distribusi');
        });
    }
}
