<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repository\Eloquent\RoleRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Illuminate\Contracts\Mail\Mailer;
use Requests;

class SendReminderEmail implements ShouldQueue

{
    use InteractsWithQueue, SerializesModels;




    /**
     * Create a new job instance.
     *
     * @param string $user
     * @param RoleRepository $roleRepository
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RoleRepository $repository)
    {


        $data = array("src" => "# include<stdio.h> \n int main()\n  {\n printf(\"1\");\n return 0;\n}",
            "language" => "c",
            "max_cpu_time" => 1000,
            "max_memory" => 395671011,
            "test_case_id" => 1001);
        //dd($data);
        $res = Requests::post("http://hrsoft.net:8010/judge", array("token" => "hrsoft","Content-Type"=>"application/json"), json_encode($data));
        json_decode($res->body);
        $repository->insert(['name'=>'test','display_name'=>'judge','description'=>json_encode($res->body)]);
//        $this->roleRepo->insert();

        return;
    }
}
