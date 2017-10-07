<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午9:13
 */

namespace App\Http\Controllers;


use App\Common\Utils;
use App\Common\ValidationHelper;
use App\Services\PicService;
use App\Services\ProblemService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    private $projectService;
    private $problemService;
    private $picService;

    public function __construct(ProjectService $projectService, ProblemService $problemService, PicService $picService)
    {
        $this->projectService = $projectService;
        $this->problemService = $problemService;
        $this->picService = $picService;
    }

    public function create(Request $request)
    {
        $rules = [
            'title' => 'required|max:100',
            'startAt' => 'required',
            'endAt' => 'required',
            'description' => 'required|max:255',
            'type' => 'required|integer',
            'isPublic' => 'required|boolean',
            'password' => 'max:255',
            'maxChoose' => 'required|integer',
            'problemList' => 'required|array',
            'picAddress' => 'array'
        ];
        ValidationHelper::validateCheck($request->all(), $rules);

        $info = ValidationHelper::getInputData($request, $rules);

        $info = Utils::unCamelize($info);

        $problemList = $info['problem_list'];

        $picList = $info['pic_address'];

        unset($info['pic_address']);
        unset($info['problem_list']);

        $info['user_id'] = $request->user->id;


        DB::transaction(function () use ($info, $picList, $problemList) {
            if ($picList != null) {
                $info['has_pic'] = false;
            } else
                $info['has_pic'] = true;

            $projectId = $this->projectService->createProject($info);

            $this->problemService->createProblem($projectId, $problemList);

            if ($picList != null) {
                $this->picService->addPicToProject($projectId, $picList);
            }
        });

        return response()->json(
            [
                'code' => 0
            ]
        );


    }

    public function dropProject(Request $request, $projectId)
    {
        $userId = $request->user->id;

        DB::transaction(function () use ($userId, $projectId) {
            $this->projectService->deleteProject($userId, $projectId);
            $this->problemService->deleteProblem($projectId, $userId);
            $this->picService->dropPic($projectId);
        });

        return response()->json(
            [
                'code' => 0
            ]
        );
    }
}