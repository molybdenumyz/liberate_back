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
use App\Exceptions\chooseNumException;
use App\Exceptions\passwordNeedException;
use App\Exceptions\Permission\PermissionDeniedException;
use App\Services\PicService;
use App\Services\ProblemService;
use App\Services\ProjectService;
use App\Services\RecordService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    private $projectService;
    private $problemService;
    private $picService;
    private $recordService;
    private $tokenService;

    public function __construct(TokenService $tokenService, ProjectService $projectService, ProblemService $problemService, PicService $picService, RecordService $recordService)
    {
        $this->projectService = $projectService;
        $this->problemService = $problemService;
        $this->picService = $picService;
        $this->recordService = $recordService;
        $this->tokenService = $tokenService;
    }

    public function create(Request $request)
    {
        $rules = [
            'title' => 'required|max:100',
            'startAt' => 'required|integer',
            'endAt' => 'required|integer',
            'description' => 'max:255',
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

        if (!$info['is_public']) {
            if ($info['password'] == null)
                throw new passwordNeedException();
        }
        DB::transaction(function () use ($info, $picList, $problemList) {


            $projectId = $this->projectService->createProject($info);

            $this->problemService->createProblem($projectId, $problemList);

            if ($picList != null) {
                $info['has_pic'] = true;
                $this->picService->addPicToProject($projectId, $picList);
            }else
                $info['has_pic'] = false;
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

    public function vote(Request $request, $projectId)
    {
        $rules = [
            'options' => 'required|array'
        ];

        $info = ValidationHelper::checkAndGet($request, $rules)['options'];

        $data = $this->projectService->getProjectDetail($projectId);

        if ($data['maxChoose'] != count($info)) {
            throw new chooseNumException();
        }
        $userId = -1;
        if ($request->hasHeader('token')) {
            $userId = $this->tokenService->getUserIdByToken($request->header('token'));
        }
        $ip = $request->ip();

        $this->recordService->addRecord($projectId, $info, $userId, $ip);


        return response()->json(
            [
                'code' => 0,
                'data' => $this->problemService->getProblem($projectId)
            ]
        );
    }

    public function getProjectList(Request $request)
    {

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $type = $request->input('time', 0);
        return response()->json(
            [
                'code' => 0,
                'data' => $this->projectService->getProjectList($page, $size, $type)
            ]
        );

    }

    public function getProjectDetail(Request $request, $projectId)
    {

        $rules = [
            'password' => 'max:255'
        ];

        $password = ValidationHelper::checkAndGet($request, $rules)['password'];

        $info = $this->projectService->getProjectDetail($projectId);

        if (!$info['is_public']) {
            if ($password == null || strcmp($info['password'], $password))
                throw new PermissionDeniedException();
        }

        $data = $this->problemService->getProblemBeforeVote($projectId);

        $data['title'] = $info['title'];
        $data['type'] = $info['type'];
        $data['maxChoose'] = $info['max_choose'];
        $data['startAt'] = $info['start_at'];
        $data['endAt'] = $info['end_at'];
        return response()->json(
            [
                'code' => 0,
                'data' => $data
            ]
        );
    }


}