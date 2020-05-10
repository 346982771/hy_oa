<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Controller;
class UpFiles extends Common
{

    public function upload(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            //$result['url'] =$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':8089/public'.'/uploads/'. $path;
            $result['url'] =$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/public'.'/uploads/'. $path;
            //$result['url'] = '/public'.'/uploads/'. $path;
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =0;
            $result['info'] = '图片上传失败!';
            $result['url'] = '';
            return $result;
        }
    }

    public function uploads()
    {
        $pathName = $this->request->param('path');//图片存放的目录
        $file = request()->file('file');//获取文件信息
        $path = 'public/' . (!empty($pathName) ? $pathName : 'uploads');//文件目录

        //创建文件夹
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $info = $file->move($path);//保存在目录文件下
        if ($info && $info->getPathname()) {
            $data = [
                'status' => 1,
                //'data' =>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].':8089/'. $info->getPathname(),
                'data' =>$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/'. $info->getPathname(),
                //'data' => '/'. $info->getPathname(),
            ];
            echo exit(json_encode($data));
        } else {
            echo exit(json_encode($file->getError()));
        }
    }


    public function file(){
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

        if($info){
            $result['code'] = 0;
            $result['info'] = '文件上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());

            $result['url'] = '/uploads/'. $path;
            $result['ext'] = $info->getExtension();
            $result['size'] = byte_format($info->getSize(),2);
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['info'] = '文件上传失败!';
            $result['url'] = '';
            return $result;
        }
    }

    public function pic(){
        // 获取上传文件表单字段名
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 1;
            $result['info'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['url'] = '/uploads/'. $path;
            return json_encode($result,true);
        }else{
            // 上传失败获取错误信息
            $result['code'] =0;
            $result['info'] = '图片上传失败!';
            $result['url'] = '';
            return json_encode($result,true);
        }
    }
    //编辑器图片上传
    public function editUpload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result['data']['src'] = __PUBLIC__.'/uploads/'. $path;
            $result['data']['title'] = $path;
            return json_encode($result,true);
        }else{
            // 上传失败获取错误信息
            $result['code'] =1;
            $result['msg'] = '图片上传失败!';
            $result['data'] = '';
            return json_encode($result,true);
        }
    }
    //多图上传
    public function upImages(){
        $fileKey = array_keys(request()->file());
        // 获取表单上传文件
        $file = request()->file($fileKey['0']);
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        if($info){
            $result['code'] = 0;
            $result['msg'] = '图片上传成功!';
            $path=str_replace('\\','/',$info->getSaveName());
            $result["src"] = '/uploads/'. $path;
            return $result;
        }else{
            // 上传失败获取错误信息
            $result['code'] = 1;
            $result['msg'] = '图片上传失败!';
            return $result;
        }
    }
}