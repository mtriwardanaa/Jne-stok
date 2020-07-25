<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveToOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_order_detail', function (Blueprint $table) {
            $table->string('jumlah_approve')->nullable()->after('qty_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok_order_detail', function (Blueprint $table) {
            $table->dropColumn('jumlah_approve');
        });
    }
}
