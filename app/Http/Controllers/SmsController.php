<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alphanetbd\Alphasms\AlphaSMS;


class SmsController extends Controller
{
    public function send(){
        $sms = new AlphaSMS();

       $resp = $sms->sendSMS('Hello World!', '0123456789');

        dd($resp);

        return view('welcome');
    }
}
