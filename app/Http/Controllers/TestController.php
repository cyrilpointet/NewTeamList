<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Kutia\Larafirebase\Facades\Larafirebase;


class TestController extends Controller
{
    public function test()
    {
        $homeLink = url('/');
        Mail::to('toto@toto.toto')->send(new InvitationMail([
            "name" => 'Pouic',
            "link" => $homeLink
        ]));
        return response('Yop', 200);
    }
}
