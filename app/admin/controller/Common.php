<?php
namespace app\admin\controller;
use think\Exception;
use think\Request;
use think\Db;
use think\Controller;
class Common extends Controller
{

    protected $mod,$role,$system,$nav,$menudata,$cache_model,$categorys,$module,$moduleid,$adminRules,$HrefId;
    public function _initialize()
    {
        //判断管理员是否登录
        if (!session('aid')) {
            $this->redirect('login/index');
        }
        define('MODULE_NAME',strtolower(request()->controller()));
        define('ACTION_NAME',strtolower(request()->action()));

        //权限管理
        //当前操作权限ID
        if(session('aid') != 1){
            $this->HrefId = db('auth_rule')->where('href',MODULE_NAME.'/'.ACTION_NAME)->value('id');

            //当前管理员权限
            $map['a.admin_id'] = session('aid');
            $groups = Db::table(config('database.prefix').'admin')->alias('a')
                ->where($map)
                ->value('a.group_id');

            $rules = db('auth_group')->where('group_id in('.$groups.')')->value('rules');

//            $total_rules = '';
//            foreach($rules as $rule){
//                $total_rules .= $rule;
//            }
            $this->adminRules = explode(',',$rules);
            if($this->HrefId){
                if(!in_array($this->HrefId,$this->adminRules)){
                    $this->error('您无此操作权限','index');
                }
            }
        }
        try{
            //db('log')->insert(['loginname' => session('username'),'username' => session('loginname'),'rule' => 'admin/'.MODULE_NAME.'/'.ACTION_NAME,'create_time' => time()]);
        }catch (\Exception $e){

        }
    }
    public function deToken()
    {

    }
    public function enToken()
    {
        $data['token'] = json_encode(base64_encode(time() . getRandomString(16)));
        $data["token_validity"] = time() + 3600 * 30;
        return $data['token'];
    }
    //空操作
    public function _empty(){
        return $this->error('空操作，返回上次访问页面中...');
    }
}
