<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Person;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class PersonController extends AdminController
{
    protected $sexOpts = [0 => "保密", 1 => "男", 2 => "女"];
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Person(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('age');
            $grid->column('sex')->using($this->sexOpts);
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
        return Show::make($id, new Person(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('age');
            $show->field('sex')->using($this->sexOpts);
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
        return Form::make(new Person(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->number('age');
            $form->radio('sex')->options($this->sexOpts);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
