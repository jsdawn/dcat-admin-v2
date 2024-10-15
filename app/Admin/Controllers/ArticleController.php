<?php
namespace App\Admin\Controllers;

use App\Admin\Repositories\Article;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

class ArticleController extends AdminController
{

    protected $typeOpts   = \App\Models\Article::TYPE;
    protected $statusOpts = \App\Models\Article::STATUS;

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Article(['author', 'comments']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('image');
            $grid->column('title');
            $grid->column('type')->using($this->typeOpts);
            $grid->column('brief');
            $grid->column('content');
            $grid->column('status')->using($this->statusOpts);
            $grid->column('like_count');
            // $grid->column('comment_count');
            $grid->comments('评论数量')->display(function ($comments) {
                return count($comments);
            });
            $grid->column('collect_count');
            // $grid->column('user_id');
            $grid->column('author.name', '作者');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
        });
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
        return Show::make($id, new Article(['author']), function (Show $show) {
            $show->field('id');
            $show->field('image');
            $show->field('title');
            $show->field('type')->using($this->typeOpts);
            $show->field('brief');
            $show->field('content');
            $show->field('status')->using($this->statusOpts);
            $show->field('like_count');
            $show->field('comment_count');
            $show->field('collect_count');
            // $show->field('user_id');
            $show->field('author.name', '作者');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Article(), function (Form $form) {
            $form->display('id');
            $form->text('image');
            $form->text('title');

            $form->select('type')
                ->options($this->typeOpts)
                ->required();

            $form->text('brief');
            $form->text('content')->required();

            $form->select('status')
                ->options($this->statusOpts)
                ->required();

            $form->display('like_count');
            $form->display('comment_count');
            $form->display('collect_count');
            $form->text('user_id');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
