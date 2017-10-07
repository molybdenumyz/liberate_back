<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午9:00
 */

namespace App\Repository\Eloquent;


class ProblemRepository extends AbstractRepository
{

    function model()
    {
        return "App\Repository\Models\Problem";
    }
}