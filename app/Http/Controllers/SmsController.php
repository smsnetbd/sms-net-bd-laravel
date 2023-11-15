<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alphanetbd\Alphasms\AlphaSMS;


class SmsController extends Controller
{
    public function send(){
        $sms = new AlphaSMS();
        return view('welcome');
    }
}
