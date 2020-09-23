<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_barang', function (Blueprint $table) {
            $table->integer('internal')->default(1)->after('id_barang_satuan');
            $table->integer('agen')->default(1)->after('internal');
            $table->integer('subagen')->default(1)->after('agen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_barang', function (Blueprint $table) {
            $table->dropColumn('internal');
            $table->dropColumn('agen');
            $table->dropColumn('subagen');
        });
    }
}
