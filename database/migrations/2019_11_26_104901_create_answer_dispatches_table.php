<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_dispatches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('answer_id');
            $table->bigInteger('problem_id');
            $table->bigInteger('user_id');
            $table->boolean('solved')->default(false);
            $table->integer('score')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_dispatchs');
    }
}
