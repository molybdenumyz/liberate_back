<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/8
 * Time: 下午3:28
 */

namespace App\Exceptions\File;


use App\Exceptions\BaseException;

class OnlySupportImage extends BaseException
{
    protected $code = 60002;
    protected $data = "仅支持图片";
}