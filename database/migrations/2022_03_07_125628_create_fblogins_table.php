<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFbloginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fblogins', function (Blueprint $table) {
            $table->id();
			$table->string('userRef');
			$table->string('accessToken');
			$table->string('fbUserId');
			$table->integer('status');
			$table->string('appId');
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
        Schema::dropIfExists('fblogins');
    }
}
