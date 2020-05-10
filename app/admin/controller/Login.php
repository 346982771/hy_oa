<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;
class Login extends Controller
{
    public function _initialize(){
        if (session('aid')) {
            $this->redirect('index/index');
        }
    }
    private $cache_model,$system;
    public function index(){
        if(request()->isPost()) {
            $admin = new Admin();
            $data = input('post.');
//            print_r($data);die;
//            if(!$this->check($data['captcha'])){
//                return json(array('code' => 0, 'msg' => '验证码错误'));
//            }

            $num = $admin->login($data);
            if($num == 1){
                //write_log("public/log/login.txt","public/log/",'IP：'.request()->ip().'时间：'.date('Y-m-d H:i:s',time())."\r\n");
                return json(['code' => 1, 'msg' => '登录成功!', 'url' => url('index/index')]);
            }else {
                return json(array('code' => 0, 'msg' => '用户名或者密码错误，重新输入!'));
            }
        }else{

            return $this->fetch();
        }
    }
    public function check($code){
       return captcha_check($code);
    }
}