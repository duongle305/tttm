<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

    class CreateAssetTempTransfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_temp_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->unsignedInteger('current_warehouse_id');
            $table->foreign('current_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedInteger('next_warehouse_id');
            $table->foreign('next_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_temp_transfers');
    }
}
