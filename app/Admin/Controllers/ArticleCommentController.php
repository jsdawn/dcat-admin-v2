<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\ArticleComment;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class ArticleCommentController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(
            new ArticleComment(['user', 'article', 'toUser']),
            function (Grid $grid) {
                $grid->column('id')->sortable();
                $grid->column('content');
                $grid->column('like_count');
                // $grid->column('user_id');
                $grid->column('user.name', admin_trans('user.fields.name'));
                // $grid->column('to_user_id');
                $grid->column('to_user.name', '回复对象');
                // $grid->column('article_id');
                $grid->column('article.title', '文章');
                $grid->column('created_at');
                $grid->column('updated_at')->sortable();

                $grid->filter(function (Grid\Filter $filter) {
                    $filter->equal('id');

                });
            }
        );
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make(
            $id,
            new ArticleComment(['user', 'article', 'toUser']),
            function (Show $show) {
                $show->field('id');
                $show->field('content');
                $show->field('like_count');
                // $show->field('user_id');
                $show->field('user.name', admin_trans('user.fields.name'));
                // $show->field('to_user_id');
                $show->field('to_user.name', '回复对象');
                // $show->field('article_id');
                $show->field('article.title', '文章');
                $show->field('created_at');
                $show->field('updated_at');
            }
        );
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new ArticleComment(), function (Form $form) {
            $form->display('id');
            $form->text('content');
            $form->text('like_count');
            $form->text('user_id');
            $form->text('to_user_id');
            $form->text('article_id');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
