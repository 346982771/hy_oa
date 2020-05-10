<?php
namespace app\admin\model;
use think\Model;
class Admin extends Model
{
	public function login($data){
		$user=db('admin')->where('loginname',$data['loginname'])->find();
		if($user){
			if($user['password'] == md5($data['password'])){
				session('loginname',$user['loginname']);
                session('username',$user['username']);
				session('aid',$user['admin_id']);
                session('station',$user['station']);
				$avatar = $user['img']==''?'/public/static/admin/images/0.jpg':$user['img'];
				session('avatar',$avatar);
				$logData = [];
				$logData['logtype'] = 1;
				$logData['loginname'] = $user['loginname'];
				$logData['username'] = $user['username'];
				$logData['rule'] = '登录后台管理系统';
				$logData['create_time'] = time();
				return 1; //信息正确
			}else{
				return -1; //密码错误
			}
		}else{
			return -1; //用户不存在
		}
	}

}
