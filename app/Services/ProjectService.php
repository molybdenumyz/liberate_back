<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午9:53
 */

namespace App\Services;


use App\Common\Utils;
use App\Exceptions\Permission\PermissionDeniedException;
use App\Exceptions\projectNotExistException;
use App\Repository\Eloquent\ProjectRepository;
use App\Services\Contracts\ProjectServiceInterface;

class ProjectService implements ProjectServiceInterface
{
    private $projectRepo;

    function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepo = $projectRepository;
    }

    function createProject(array $info)
    {
        return $this->projectRepo->insertWithId($info);
    }

    function deleteProject($userId, $projectId)
    {
        if ($this->projectRepo->getWhereCount(['id' => $projectId]) != 1)
            throw new projectNotExistException();

        $rows = $this->projectRepo->deleteWhere(['user_id' => $userId, 'id' => $projectId]);

        if ($rows != 1)
            throw new PermissionDeniedException();

        return true;

    }

    function getProjectList($page, $rows, $type)
    {
        $time = Utils::createTimeStamp();

        //0为未开始
        if ($type == 0) {
            $info = $this->projectRepo->paginate($page, $rows, [['start_at', '>', $time]], ['id',
                'title',
                'start_at',
                'end_at',
                'type',
                'is_public',
                'max_choose',
                'has_pic']);

            $count = $this->projectRepo->getWhereCount([['start_at', '>', $time]]);

        } elseif ($type == 1) {
            $info = $this->projectRepo->paginate($page, $rows, [['start_at', '<', $time],
                ['end_at', '>', $time]],
                ['id',
                    'title',
                    'start_at',
                    'end_at',
                    'type',
                    'is_public',
                    'max_choose',
                    'has_pic']);
            $count = $this->projectRepo->getWhereCount([['start_at', '<', $time],
                ['end_at', '>', $time]]);
        } else {
            $info = $this->projectRepo->paginate($page, $rows, [],
                ['id',
                    'title',
                    'start_at',
                    'end_at',
                    'type',
                    'is_public',
                    'max_choose',
                    'has_pic']);
            $count = $this->projectRepo->getWhereCount();
        }


        $info = $info->toArray();

        foreach ($info as &$item) {
            $item = Utils::camelize($item);
        }


        return [
            'list' => $info,
            'count' => $count
        ];
    }

    function getProjectDetail($projectId)
    {
        $info = $this->projectRepo->get($projectId);

        $info = Utils::camelize($info);

        return $info;
    }


}