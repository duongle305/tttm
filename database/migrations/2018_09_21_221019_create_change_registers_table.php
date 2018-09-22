<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_registers', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date'); // ngày
            $table->unsignedInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('cr_number'); // số cr
            $table->text('content')->nullable(); // nội dung
            $table->text('purpose')->nullable(); // mục đích
            $table->unsignedInteger('combinator_id')->nullable(); // Người phối hợp
            $table->foreign('combinator_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('combine_phone_nb')->nullable(); // số điện thoại phối hợp
            $table->unsignedInteger('executor_id'); // Người thực hiện
            $table->foreign('executor_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('execute_content')->nullable(); // Nội dung thực hiện
            $table->unsignedInteger('tester_id')->nullable(); // Người kiểm tra
            $table->foreign('tester_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('result')->nullable(); // kết quả
            $table->text('note')->nullable(); // ghi chú
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('user_id')->nullable(); // người tạo việc
            $table->text('indexes')->nullable();
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
        Schema::dropIfExists('change register');
    }
}
