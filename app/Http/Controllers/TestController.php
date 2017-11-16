<?php

namespace App\Http\Controllers;

use App\Jobs\SendReminderEmail;
use App\Repository\Eloquent\RoleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Requests;


class TestController extends Controller
{
    function test(){

       $this->dispatch(new SendReminderEmail());




    }
}
