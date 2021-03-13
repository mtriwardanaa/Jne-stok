<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalRejectToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_order', function (Blueprint $table) {
            $table->dateTime('tanggal_reject')->nullable()->after('tanggal_approve');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_order', function (Blueprint $table) {
            $table->dropColumn('tanggal_reject');
        });
    }
}
