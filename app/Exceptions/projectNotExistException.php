<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:40
 */

namespace App\Exceptions;


class projectNotExistException extends BaseException
{
    protected $code = 50001;

    protected $data = "该投票不存在";
}