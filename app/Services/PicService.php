<?php
/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/7
 * Time: 下午10:18
 */

namespace App\Services;


use App\Exceptions\File\OnlySupportImage;
use App\Exceptions\File\PicUploadFailException;
use App\Repository\Eloquent\PicRepository;
use App\Services\Contracts\PicServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PicService implements PicServiceInterface
{

    private $picRepo;


    public function __construct(PicRepository $picRepo)
    {
        $this->picRepo = $picRepo;
    }


    public function uploadPic(UploadedFile $file)
    {
        if ($file->isValid()) {
            // 获取文件相关信息
            $originalName = $file->getClientOriginalName(); // 文件原名
            $ext = $file->getClientOriginalExtension();     // 扩展名
            if ($ext != 'png'&&$ext != 'jpeg'&&$ext!='jpg')
                throw new OnlySupportImage();
            $realPath = $file->getRealPath();   //临时文件的绝对路径
            $type = $file->getClientMimeType();     // image/jpeg
            // 上传文件
            $filename = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
            // 使用我们新建的uploads本地存储空间（目录）
            $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));

            if (!$bool) {
                throw new PicUploadFailException();
            }

            $filePath = 'upload/' . $filename;

            return $filePath;
        }
        return false;
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


        DB::transaction(function ()use($projectId){
            $infos = $this->picRepo->getBy('project_id',$projectId,['address']);
            foreach ($infos as $info){
                unlink(public_path('upload').$info['address']);
            }
           $this->picRepo->deleteWhere(['project_id'=>$projectId]);
        });

        return true;
    }


}