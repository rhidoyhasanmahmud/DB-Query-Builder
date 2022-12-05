<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndTotalCreditColumnsToUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1);
            $table->double('total_credit')->default(1);
        });
    }


    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
