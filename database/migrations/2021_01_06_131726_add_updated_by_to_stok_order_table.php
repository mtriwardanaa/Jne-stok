<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedByToStokOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stok_order', function (Blueprint $table) {
            $table->bigInteger('updated_by')->unsigned()->nullable()->after('created_by');
            $table->bigInteger('rejected_by')->unsigned()->nullable()->after('approved_by');
            $table->text('rejected_text')->nullable()->after('rejected_by');
            $table->dateTime('tanggal_update')->nullable()->after('tanggal');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('rejected_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('stok_order_updated_by_foreign');
            $table->dropIndex('stok_order_updated_by_foreign');
            $table->dropColumn('updated_by');

            $table->dropForeign('stok_order_rejected_by_foreign');
            $table->dropIndex('stok_order_rejected_by_foreign');
            $table->dropColumn('rejected_by');
            $table->dropColumn('tanggal_update');
            $table->dropColumn('rejected_text');
        });
    }
}
