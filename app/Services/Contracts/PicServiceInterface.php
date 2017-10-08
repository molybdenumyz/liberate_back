<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:17
 */

namespace App\Services\Contracts;


use Illuminate\Http\UploadedFile;

interface PicServiceInterface
{
    function uploadPic(UploadedFile $file);

    function addPicToProject($projectId,$picList);

    function dropPic($projectId);

}