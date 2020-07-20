<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserUpdateToStokBarangMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang_masuk', function (Blueprint $table) {
            $table->bigInteger('updated_by')->unsigned()->nullable()->after('created_by');

            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_barang_masuk', function (Blueprint $table) {
            $table->dropForeign('stok_barang_masuk_updated_by_foreign');
            $table->dropIndex('stok_barang_masuk_updated_by_foreign');

            $table->dropColumn('updated_by');
        });
    }
}
