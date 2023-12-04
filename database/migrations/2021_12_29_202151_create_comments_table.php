<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('description')->nullable(false);
            $table->unsignedBigInteger('author_id')->nullable(false);
            $table->unsignedBigInteger('reply_to_comment_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable(false);
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reply_to_comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
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
        Schema::dropIfExists('comments');
    }
}
