<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content')->comment('内容');
            $table->bigInteger('like_count')->default('0')->nullable()->comment('点赞数');
            $table->bigInteger('user_id')->comment('用户id');
            $table->bigInteger('to_user_id')->nullable()->comment('回复用户');
            $table->bigInteger('article_id')->comment('文章id');
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
        Schema::dropIfExists('article_comments');
    }
}
