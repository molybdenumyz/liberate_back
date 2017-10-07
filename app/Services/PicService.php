<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:18
 */

namespace App\Services;


use App\Repository\Eloquent\PicRepository;
use App\Services\Contracts\PicServiceInterface;
use Illuminate\Support\Facades\DB;

class PicService implements PicServiceInterface
{

    private $picRepo;


    public function __construct(PicRepository $picRepo)
    {
        $this->picRepo = $picRepo;
    }


    function uploadPic()
    {
        // TODO: Implement uploadPic() method.
    }

    function addPicToProject($projectId, $picList)
    {
        DB::transaction(function ()use($projectId,$picList){
            foreach ($picList as $pic){
                $item = [
                    'address'=>$pic,
                    'project_id'=>$projectId
                ];
                $this->picRepo->insert($item);
            }
        });

        return true;
    }

    function dropPic($projectId)
    {
        //TODO:文件处理

        DB::transaction(function ()use($projectId){
           $this->picRepo->deleteWhere(['project_id'=>$projectId]);
        });

        return true;
    }


}