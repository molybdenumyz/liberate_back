<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/12
 * Time: 下午9:34
 */

namespace App\Exceptions;


class HasVoteException extends BaseException
{
    protected $code = 50004;
    protected $data = "已经投过票";
}