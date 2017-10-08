<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/8
 * Time: 下午3:54
 */

namespace App\Exceptions;


class chooseNumException extends BaseException
{
    protected $code = 50002;
    protected $data = "选择个数不正确";
}