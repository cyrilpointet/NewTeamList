<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;


class TestController extends Controller
{
    public function teste()
    {
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        return Larafirebase::withTitle('Test Title')
            ->withBody('Test body')
            ->withImage('https://firebase.google.com/images/social.png')
            ->withIcon('https://seeklogo.com/images/F/firebase-logo-402F407EE0-seeklogo.com.png')
            ->withSound('default')
            ->withClickAction('https://www.google.com')
            ->withPriority('high')
            ->withAdditionalData([
                'color' => '#rrggbb',
                'badge' => 0,
            ])
            ->sendNotification($FcmToken);

    }

    public function test()
    {
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        return Larafirebase::withTitle('Test Title')
            ->withBody('Test body')
            ->withAdditionalData([
                'toto' => 'coucou',
                'pouet' => 'coin coin',
            ])
            ->sendMessage($FcmToken);

    }
}
