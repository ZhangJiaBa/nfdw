<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('报表名称');
            $table->string('people_log')->nullable()->commet('人员通行记录表');
            $table->string('car_log')->nullable()->commet('人员通行记录表');
            $table->string('employee')->nullable()->commet('员工基本表表');
            $table->string('update_log')->nullable()->commet('修正表');
            $table->string('car')->nullable()->commet('汽车基本表');
            $table->string('began_time')->comment('开始时间');
            $table->string('end_time')->comment('开始时间');
            $table->timestamps();
        });

        Schema::create('people_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('报表名称');
            $table->string('department')->nullable()->commet('人员通行记录表');
            $table->string('door')->nullable()->commet('人员通行记录表');
            $table->integer('time')->nullable()->commet('员工记录表');
            $table->string('card')->nullable()->commet('修正表');
        });

        Schema::create('employee', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('名称');
            $table->string('department')->nullable()->commet('部门');
        });

        Schema::create('car', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('名称');
            $table->string('department')->nullable()->commet('部门');
            $table->string('car_num')->nullable()->commet('车牌号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');

        Schema::dropIfExists('people_log');

        Schema::dropIfExists('employee');

        Schema::dropIfExists('car');

    }
}
