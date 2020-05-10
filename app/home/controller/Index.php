<?php
namespace app\home\controller;
use think\Input;
use think\Db;
use clt\Leftnav;
use think\Request;
use think\Controller;
use think\View;
class Index extends Controller{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){

    	return redirect('/admin/login/index');
        //return $this->fetch();
    }

}