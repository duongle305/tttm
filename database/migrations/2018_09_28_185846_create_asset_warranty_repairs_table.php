<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetWarrantyRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_warranty_repairs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->date('broken_date')->nullable();
            $table->text('reason')->nullable();
            $table->string('environment')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->string('indexes')->nullable();
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
        Schema::dropIfExists('asset_warranty_repairs');
    }
}
