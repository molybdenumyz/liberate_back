<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午9:54
 */

namespace App\Services\Contracts;


interface ProjectServiceInterface
{
    function createProject(array $info);

    function deleteProject($userId, $projectId);

    function getProjectList($page, $rows, $type);

    function getProjectDetail($projectId);
}