<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午8:58
 */

namespace App\Repository\Eloquent;


class PicRepository extends AbstractRepository
{

    function model()
    {
        return "App\Repository\Models\Pic";
    }
}