<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\User;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new User(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('avatar');
            $grid->column('name');
            $grid->column('gender')->using(admin_trans('user.options.gender'));
            $grid->column('email');
            $grid->column('status')->using(admin_trans('user.options.status'));
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
        return Show::make($id, new User(), function (Show $show) {
            $show->field('id');
            $show->field('avatar');
            $show->field('name');
            $show->field('gender')->using(admin_trans('user.options.gender'));
            $show->field('email');
            $show->field('status')->using(admin_trans('user.options.status'));
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
        return Form::make(new User(), function (Form $form) {
            $form->display('id');
            $form->text('avatar');
            $form->text('name');
            $form->select('gender')->options(admin_trans('user.options.gender'));
            $form->text('email');
            $form->select('status')->options(admin_trans('user.options.status'));

            $form->text('password')->saving(function ($pwd) {
                return Hash::make($pwd);
            });

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
