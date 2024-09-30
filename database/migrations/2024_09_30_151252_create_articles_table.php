<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image')->nullable()->comment('封面');
            $table->string('title')->default('')->comment('标题');
            $table->tinyInteger('type')->default('1')->nullable()->comment('类型');
            $table->string('brief')->nullable()->comment('简介');
            $table->longText('content')->nullable()->comment('内容');
            $table->tinyInteger('status')->default('1')->nullable()->comment('状态');
            $table->bigInteger('like_count')->default('0')->nullable()->comment('点赞数');
            $table->bigInteger('comment_count')->default('0')->nullable()->comment('评论数');
            $table->bigInteger('collect_count')->default('0')->nullable()->comment('收藏数');
            $table->bigInteger('user_id')->comment('用户id');
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
        Schema::dropIfExists('articles');
    }
}
