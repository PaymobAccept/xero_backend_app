<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacebooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fbappdetails', function (Blueprint $table) {
            $table->id();
			$table->string('userRef');
			$table->string('fbAppId');
			$table->string('fbAppSecret');
			$table->string('fbApiVersion');
			$table->string('fbScope');
			$table->string('fbAccessToken');
			$table->string('accountId');
			$table->integer('status');
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
        Schema::dropIfExists('fbappdetails');
    }
}
