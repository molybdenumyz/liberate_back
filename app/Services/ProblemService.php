<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:02
 */

namespace App\Services;


use App\Common\Utils;
use App\Repository\Eloquent\ProblemRepository;
use App\Services\Contracts\ProblemServiceInterface;
use Illuminate\Support\Facades\DB;

class ProblemService implements ProblemServiceInterface
{

    private $problemRepo;


    public function __construct(ProblemRepository $problemRepo)
    {
        $this->problemRepo = $problemRepo;
    }

    function createProblem($projectId, $problemList)
    {
        DB::transaction(function () use ($projectId, $problemList) {
            foreach ($problemList as $problem) {
                $item = [
                    'title' => $problem,
                    'project_id' => $projectId
                ];
                $this->problemRepo->insert($item);
            }
        });

        return true;
    }

    function deleteProblem($projectId, $userId)
    {

        DB::transaction(function () use ($projectId) {
            $this->problemRepo->deleteWhere(['project_id' => $projectId]);
        });

        return true;
    }

    function addProblemChooseNum($problemId)
    {
        return $this->problemRepo->update(['count' =>
            $this->problemRepo->get($problemId, ['count'])->count +1],
            $problemId);
    }

    function getProblem($projectId)
    {
        $infos = $this->problemRepo->getBy('project_id', $projectId, ['id', 'title', 'count'])->toArray();

        return $infos;
    }

    function getProblemBeforeVote($projectId)
    {
        $data = $this->problemRepo->getBy('project_id', $projectId, ['id', 'title'])->toArray();

        $infos['options'] = $data;


        return $infos;
    }


}