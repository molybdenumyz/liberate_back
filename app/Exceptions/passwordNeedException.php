<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/8
 * Time: 下午4:25
 */

namespace App\Exceptions;


class passwordNeedException extends BaseException
{
    protected $code = 50003;
    protected $data = "非公开投票需要密码";
}