<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
use app\admin\model\Config as ConfigModel;
class Config extends Common
{
    /********************************站点管理*******************************/
    //config
    public function index(){
        $table = db('config');
        if(request()->isPost()) {
            $datas = input('post.');
            if($datas['id']){
                $res = $table->where('id',$datas['id'])->update($datas);
            }else{
                $res = $table->insert($datas);
            }
            if($res){
                return json(['code' => 1, 'msg' => '保存成功!', 'url' => url('config/index')]);
            }else{
                return json(array('code' => 0, 'msg' =>'保存失败！'));
            }
        }else{
            $config = $table->find();
            $this->assign('config', $config);
            return $this->fetch('config');
        }
    }

}
