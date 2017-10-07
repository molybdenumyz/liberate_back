<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午9:01
 */

namespace App\Repository\Eloquent;


use App\Repository\Traits\InsertWithIdTrait;

class ProjectRepository extends AbstractRepository
{

    function model()
    {
        return "App\Repository\Models\Project";
    }

    use InsertWithIdTrait;
}