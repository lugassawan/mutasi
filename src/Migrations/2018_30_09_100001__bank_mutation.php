<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BankMutation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_mutation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank', 20);
            $table->string('description', 250)->nullable();
            $table->string('type', 10);
            $table->integer('total');
            $table->string('balanceposition', 20)->nullable();
            $table->date('date');
            $table->date('checkdate');
            $table->dateTime('checkdatetime');
            $table->string('hash', 40);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_mutation');
    }
}
