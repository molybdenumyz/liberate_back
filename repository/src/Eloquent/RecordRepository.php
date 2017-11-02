<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午9:03
 */

namespace App\Repository\Eloquent;


class RecordRepository extends AbstractRepository
{

    function model()
    {
        return "App\Repository\Models\Record";
    }


    public function showPartInVote($userId){
        return $this->model
            ->where('user_id',$userId)
            ->join('projects','projects.id','=','records.project_id')
            ->select('projects.*')
            ->distinct()
            ->get();
    }


}