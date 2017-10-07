<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:17
 */

namespace App\Services\Contracts;


interface PicServiceInterface
{
    function uploadPic();

    function addPicToProject($projectId,$picList);

    function dropPic($projectId);

}