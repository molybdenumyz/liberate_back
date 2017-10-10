<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:28
 */

namespace App\Services;


use App\Repository\Eloquent\RecordRepository;
use App\Services\Contracts\RecordServiceInterface;
use Illuminate\Support\Facades\DB;

class RecordService implements RecordServiceInterface
{
    private $recordRepo;
    private $problemService;

    public function __construct(ProblemService $problemService, RecordRepository $recordRepo)
    {
        $this->recordRepo = $recordRepo;
        $this->problemService = $problemService;
    }

    function addRecord($projectId, array $problemIds, $userId = null, $ip)
    {
        DB::transaction(function () use ($projectId, $problemIds, $userId, $ip) {
            foreach ($problemIds as $problemId) {

                $item = [
                    'ip' => $ip,
                    'problem_id' => $problemId,
                    'user_id' => $userId,
                    'project_id' => $projectId
                ];

                $this->recordRepo->insert($item);

                $this->problemService->addProblemChooseNum($problemId);
            }
        });

        return true;
    }

    function dropRecord($projectId)
    {
        DB::transaction(function () use ($projectId) {
            $this->recordRepo->deleteWhere(['project_id' => $projectId]);
        });

        return true;
    }

}