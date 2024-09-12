<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Approval;
use App\Admin\Repositories\Person;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class ApprovalController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Approval(['person']), function (Grid $grid) {
            $grid->column('id')->sortable();

            // 联表查询（Model关联数据表）
            // $grid->column('pid')->display(function ($pid) {
            //     return Person::find($pid)->name;
            // });
            $grid->column('person.name', '员工');

            $grid->column('content');
            $grid->column('status');
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
        return Show::make($id, new Approval(), function (Show $show) {
            $show->field('id');
            $show->field('pid');
            $show->field('content');
            $show->field('status');
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
        return Form::make(new Approval(), function (Form $form) {
            $form->display('id');
            $form->text('pid');
            $form->text('content');
            $form->text('status');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
