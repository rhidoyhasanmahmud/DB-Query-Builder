<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTeachersTable extends Migration
{

    public function up()
    {
        Schema::create('course_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_name');
            $table->integer('department_id')->unsigned()->index();
            $table->timestamps();
        });

        Schema::table('course_teachers', function($table) {
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('course_teachers');
    }
}
