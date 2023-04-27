<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlanComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan-comment', function (Blueprint $table) {
            $table->id();
            $table->string('pid');
            $table->text('comment');
            $table->string('stars');
            $table->string('comment_acc');
            $table->string('comment_email');
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
        Schema::dropIfExists('plan-comment');
    }
}
