<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/8
 * Time: 下午2:06
 */

namespace App\Http\Controllers;


use App\Common\ValidationHelper;
use App\Services\PicService;
use Illuminate\Http\Request;

class PicController
{
    private $picService;


    public function __construct(PicService $picService)
    {
        $this->picService = $picService;
    }

    public function uploadPic(Request $request)
    {

        if ($request->isMethod('post') && $request->hasFile('upload')) {



            $file = $request->File('upload');

            $data = $this->picService->uploadPic($file);
        } else
            $data = null;

        return response()->json(
            [
                'code' => 0,
                'data' => $data
            ]
        );
    }


}