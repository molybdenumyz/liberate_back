<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:00
 */

namespace App\Services\Contracts;


interface ProblemServiceInterface
{
    function createProblem($projectId, $problemList);

    function deleteProblem($projectId,$userId);

    function addProblemChooseNum($problemId);

    function getProblem($projectId);
}