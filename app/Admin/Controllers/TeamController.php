<?php

namespace App\Admin\Controllers;

use App\Models\Team;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TeamController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Team';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Team());

        $grid->disableCreateButton();

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('created_at', __('Created at'))->display(function ($dateInString) {
            $date = date_create($dateInString);
            return date_format($date, 'd/m/Y');
        });
        $grid->column('updated_at', __('Updated at'))->display(function ($dateInString) {
            $date = date_create($dateInString);
            return date_format($date, 'd/m/Y');
        });
        $grid->members()->display(function ($members) {
            return count($members);
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
        $show = new Show(Team::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->members('Members', function ($teams) {
            $teams->id();
            $teams->name();
            $teams->created_at();
            $teams->updated_at();
        });
        $show->posts('Items', function ($teams) {
            $teams->id();
            $teams->title();
            $teams->created_at();
            $teams->updated_at();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Team());

        $form->text('name', __('Name'));

        return $form;
    }
}
