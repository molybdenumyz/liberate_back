<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:24
 */

namespace App\Services\Contracts;


interface RecordServiceInterface
{
    function addRecord($projectId,array $problemIds,$userId,$ip);

    function dropRecord($projectId);
}