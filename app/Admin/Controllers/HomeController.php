<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Team;
use App\Models\User;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $users = User::all()->count();
        $teams = Team::all()->count();
        $posts = Post::all()->count();
        return $content->view('dashboard', [
            'users' => $users,
            'teams' => $teams,
            'posts' => $posts
        ]);
    }
}
