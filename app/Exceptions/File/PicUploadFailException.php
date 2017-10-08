<?php


namespace App\Exceptions\File;


use App\Exceptions\BaseException;
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/8
 * Time: 下午2:19
 */
class PicUploadFailException extends BaseException
{
    protected $code = 60001;
    protected $data = "图片上传失败";
}