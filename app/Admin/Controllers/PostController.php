<?php

namespace App\Admin\Controllers;

use App\Models\Post;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Post';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post());

        $grid->disableCreateButton();

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('created_at', __('Created at'))->display(function ($dateInString) {
            $date = date_create($dateInString);
            return date_format($date, 'd/m/Y');
        });
        $grid->column('updated_at', __('Updated at'))->display(function ($dateInString) {
            $date = date_create($dateInString);
            return date_format($date, 'd/m/Y');
        });
        $grid->team()->display(function ($team) {
            return $team['name'];
        });
        $grid->user()->display(function ($user) {
            return $user['email'];
        });
        $grid->column('user_id', __('User id'));

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
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->team('Team', function ($team) {
            $team->id();
            $team->name();
        });
        $show->user('User', function ($user) {
            $user->id();
            $user->name();
        });
        $show->field('title', __('Title'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Post());

        $form->text('title', __('Title'));

        return $form;
    }
}
