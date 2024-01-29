<?php

namespace App\Http\Controllers;
use App\Mail\DevisSendMail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Mail as FacadesMail;

class MailController extends Controller
{
    public function index(){
        $mailData = [
            'title'=>"Mail de notification",
            'body'=>"Je teste le mail de notification",
        ];
        Mail::to('amdysarr94@gmail.com')->send(new DevisSendMail($mailData));
    }
}
