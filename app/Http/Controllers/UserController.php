<?php

namespace App\Http\Controllers;

use App\Common\Utils;
use App\Common\ValidationHelper;
use App\Exceptions\Permission\PermissionDeniedException;
use App\Services\PicService;
use App\Services\ProblemService;
use App\Services\ProjectService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    private $userService;
    private $problemService;
    private $projectService;
    private $picService;
    public function __construct(ProjectService $projectService,PicService $picService,UserService $userService, ProblemService $problemService)
    {
        $this->userService = $userService;
        $this->problemService = $problemService;
        $this->picService = $picService;
        $this->projectService = $projectService;
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|max:100',
            'mobile' => 'required|mobile|max:45',
            'password' => 'required|min:6|max:20'
        ];

        ValidationHelper::validateCheck($request->all(), $rules);

        $userInfo = ValidationHelper::getInputData($request, $rules);

        // todo 添加相关激活逻辑

        $userId = $this->userService->register($userInfo);

        return response()->json([
            'code' => 0,
            'data' => [
                'user_id' => $userId
            ]
        ]);
    }

    public function login(Request $request)
    {
        $rules = [
            'identifier' => 'required|string',
            'password' => 'required|min:6|max:20',
            'client' => 'min:1|max:2' // 登录设备标识符
        ];

        if ($request->method() == "POST") {
            ValidationHelper::validateCheck($request->all(), $rules);

            // 在此定制登录方式

            $identifier = $request->identifier;

            if (Utils::isEmail($identifier)) {
                $loginMethod = 'email';
            } else if (Utils::isMobile($identifier)) {
                $loginMethod = 'mobile';
            } else {
                $loginMethod = 'name';
            }

            $data = $this->userService
                ->loginBy($loginMethod, $identifier, $request->password, $request->ip(), 1);
        } else {
            $data = $this->userService->login($request->user->id, $request->ip(), 1);
        }


        return response()->json([
            'code' => 0,
            'data' => $data
        ]);
    }

    public function logout(Request $request)
    {
        $userId = $request->user->id;

        $this->userService->logout($userId, 1);

        return response()->json(
            [
                'code' => 0
            ]
        );
    }

    public function updateUserPassword(Request $request)
    {
        $rules = [
            'oldPassword' => 'required|min:6|max:20',
            'newPassword' => 'required|min:6|max:20',
        ];

        $userPwd = ValidationHelper::checkAndGet($request, $rules);

        $userInfo = [
            'userId' => $request->user->id,
            'password' => $userPwd['oldPassword'],
            'newPassword' => $userPwd['newPassword']
        ];

        $this->userService->updateUserPassword($userInfo);

        return response()->json(
            ['code' => 0]
        );
    }


    public function showPartInVote(Request $request)
    {
        $userId = $request->user->id;

        return response()->json(
            [
                'code' => 0,
                'data' => $this->userService->showPartInVote($userId)
            ]
        );
    }

    public function showCreateVote(Request $request)
    {
        $userId = $request->user->id;

        return response()->json(
            [
                'code' => 0,
                'data' => $this->userService->showCreateVote($userId)
            ]
        );
    }


    public function statistics(Request $request, $projectId)
    {
        $voteList = $this->userService->showCreateVote($request->user->id);

        $flag = false;
        foreach ($voteList as $value) {
            if ($projectId == $value['id']) {
                $flag = true;
                break;
            }
        }
        if ($flag) {
            $data = $this->problemService->getProblem($projectId);
        } else
            throw new PermissionDeniedException();

        return response()->json([
            'code' => 0,
            'data' => $data
        ]);
    }


    public function dropProject(Request $request, $projectId)
    {
        $userId = $request->user->id;

        $voteList = $this->userService->showCreateVote($request->user->id);

        $flag = false;

        foreach ($voteList as $value) {
            if ($projectId == $value['id']) {
                $flag = true;
                break;
            }
        }
        if ($flag) {
            DB::transaction(function () use ($userId, $projectId) {
                $this->projectService->deleteProject($userId, $projectId);
                $this->problemService->deleteProblem($projectId, $userId);
                $this->picService->dropPic($projectId);
            });
        } else
            throw new PermissionDeniedException();


        return response()->json(
            [
                'code' => 0
            ]
        );
    }
}
