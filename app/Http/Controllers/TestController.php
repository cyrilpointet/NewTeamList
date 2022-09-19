<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();

        $serverKey = 'BLoWMmusb8bpzIZ9YJ37Jw9K_aTc-iy_ZMoAnHbjjYR01XAsP-uAIygCGuoyFn18gDnyb8E2mV4kMC1ZBKMHFdU:K3-V4oyF7JKBhh-zu10GKdnd1ehrfHK_ZWC8jp7Chzc';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => "title",
                "body" => "body",
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization: Bearer ' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);

        return response()->json([$result]);
    }
}
