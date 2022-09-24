<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->disableCreateButton();

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->teams()->display(function ($teams) {
            $teams = array_map(function ($team) {
                return "<span class='label label-success'>{$team['name']}</span>";
            }, $teams);
            return join('&nbsp;', $teams);
        });
        $grid->column('created_at', __('Created at'))->display(function ($dateInString) {
            $date = date_create($dateInString);
            return date_format($date, 'd/m/Y');
        });
        $grid->column('updated_at', __('Updated at'))->display(function ($dateInString) {
            $date = date_create($dateInString);
            return date_format($date, 'd/m/Y');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->teams('Teams', function ($teams) {
            $teams->id();
            $teams->name();
            $teams->created_at();
            $teams->updated_at();
        });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('device_key', __('Device key'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', __('Name'));

        return $form;
    }
}
