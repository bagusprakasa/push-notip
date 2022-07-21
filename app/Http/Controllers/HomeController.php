<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAysUUTX0:APA91bEhXrg0plVP5qyx5ZjrGbyTaYqqcKdH0zbY5cZwvKFFY_4SjtgMYmp0WfrAHnQdZe0hX7vFRwdREIRR2GvyszQS-La0hGRz1eacfXoo9wFdQZHpF2CbDhfaYuow1UbDw7rz7cyi';

        $data = [
            "registration_ids" => 'cNsjGUL_a9Rb-SPRv9sd_n:APA91bFYtaZ3R1fwsjP5Djwd35usH-RrybgLSBVVbl-QR_R3qFh9Y44-oDGl-C9cjENSHfvdJ-Lh-_NjoX6uZFy6A5EYiOMS35ywMUdADPEkTkbRcnXhS1YUk5Hpl9zkOPMjPEEPVWZS',
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }
}
