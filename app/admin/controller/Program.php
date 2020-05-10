<?php

namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Controller;
use app\admin\controller\Menu;
class Program extends Common
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //列表
    public function index()
    {
        if (request()->isPost()) {
            $key = input('post.key');
            $where = $this->request->param();
            $page = input('page') ? input('page') : 1;
            $pageSize = input('limit') ? input('limit') : config('pageSize');

            $list = db('program')->alias('p');
            if(session('station') > 0){
                $list->join('program_user pu','p.id = pu.program_id','LEFT')
                    ->where(['pu.user_id' => session('aid')]);
            }else{
                $list->where(['p.user_id' => session('aid')]);
                if($where['user_id']){
                    $list->join('program_user pu','p.id = pu.program_id','LEFT')
                        ->where(['pu.user_id' => $where['user_id']]);
                }

            }
            if($where['status'] == '0' || $where['status'] == '1'){
                $list->where(['p.status' => $where['status']]);
            }


            $list->where('p.title', 'like', "%" . $key . "%");
            $list = $list->order('p.create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();

            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            }
            return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
        }
        $user_list = db('admin')->where(['station' => ['>',0]])->select();
        $this->assign('user_list', $user_list);
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            //构建数组
            $data = input('post.');
            $data['create_time'] = time();
            $data1 = $data;
            $data['user_id'] = session('aid');
            unset($data['station1'],$data['station2'],$data['station3'],$data['station4'],$data['station5'],$data['station']);
            $program_id = db('program')->insertGetId($data);
            $program_user_data = [];
            for($i = 1;$i <= 6;$i++){
                if($data1['station'.$i]){
                    $program_user_data[] = [
                        'user_id' => $data1['station'.$i],
                        'station' => $i,
                        'program_id' => $data1['id'],
                    ];
                }
            }

            if($program_user_data){
                db('program_user')->insertAll($program_user_data);
            }


            $result['code'] = 1;
            $result['msg'] = '添加成功!';
            $result['url'] = url('index');
            return $result;
        } else {
            $user_list = db('admin')->where(['station' => ['>',0]])->select();
            $this->assign('user_list', $user_list);
            $this->assign('title', '添加信息');
            $this->assign('info', 'null');
            return $this->fetch('form');
        }
    }

    public function edit()
    {
        if (request()->isPost()) {
            $data = request()->param();
            $data['user_id'] = session('aid');
            $data1 = $data;
            unset($data['station1'],$data['station2'],$data['station3'],$data['station4'],$data['station5']);
            $program_user_data = [];

            for($i = 1;$i <= 6;$i++){
                if($data1['station'.$i]){
                    $res = db('program_user')->where(['program_id' => $data1['id'],'station' => $i,'user_id' => $data1['station'.$i]])->find();
                    if(!$res){
                        $program_user_data[] = [
                            'user_id' => $data1['station'.$i],
                            'station' => $i,
                            'program_id' => $data1['id'],
                        ];
                    }
                }
            }

            if($program_user_data){
                //db('program_user')->where(['program_id' => $data1['id']])->delete();
                db('program_user')->insertAll($program_user_data);
            }
            db('program')->where('id', $data['id'])->update($data);



            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('index');
            return $result;
        } else {
            $id = input('id');
            $adInfo = db('program')->where(['id' => $id])->find();
            $user_list = db('admin')->where(['station' => ['>',0]])->select();
            $this->assign('user_list', $user_list);
            $program_user_list = db('program_user')->where(['program_id' => $id])->select();
            $this->assign('program_user_list', $program_user_list);
            $this->assign('info', json_encode($adInfo, true));
            $this->assign('adInfo', $adInfo);
            $this->assign('title', lang('edit') . '信息');

            return $this->fetch('form');
        }
    }

    public function detailStation()
    {
        $data = request()->param();
        $program_info = db('program')->find($data['id']);

        $program_user_list = db('program_user')->alias('p')
            ->join('admin a','p.user_id = a.admin_id','LEFT')
            ->where(['p.program_id' => $data['id']])->order('station asc')->field('p.*,a.username')->select();
        if($program_user_list){

                foreach ($program_user_list as $program_user_list_k => $program_user_list_v){
                    if($data['station']){
                        if($program_user_list_v['station'] == $data['station']){
                            $program_user_list[$program_user_list_k]['log'] = db('program_log')->alias('p')
                                ->join('admin a','p.user_id = a.admin_id','LEFT')
                                ->where(['p.station' => $program_user_list_v['station']])
                                ->where(['p.program_id' => $data['id']])
                                ->field('p.*,a.username')->order('create_time desc')->select();
                        }
                    }else{
                        $program_user_list[$program_user_list_k]['log'] = db('program_log')->alias('p')
                            ->join('admin a','p.user_id = a.admin_id','LEFT')
                            ->where(['p.station' => $program_user_list_v['station']])
                            ->where(['p.program_id' => $data['id']])
                            ->field('p.*,a.username')->order('create_time desc')->select();
                    }
                }


        }
        $this->assign('program_user_list', $program_user_list);
        $this->assign('program_info', $program_info);
        $this->assign('title', '项目详情');
        return $this->fetch('detail_station');
    }
    public function detailTime()
    {

        $data = request()->param();
        $program_info = db('program')->find($data['id']);
        $program_log = db('program_log')->alias('p')
            ->join('admin a','p.user_id = a.admin_id','LEFT')
            ->where(['p.program_id' => $data['id']])
            ->field('p.*,a.username')->order('create_time desc')->select();

        $this->assign('program_log', $program_log);
        $this->assign('program_info', $program_info);
        $this->assign('title', '项目详情');
        return $this->fetch('detail_time');
    }
    //设置状态
    public function editHide()
    {
        $id = input('post.id');
        $hide = input('post.hide');
        if (db('program')->where('id=' . $id)->update(['hide' => $hide]) !== false) {
            return ['status' => 1, 'msg' => '设置成功!'];
        } else {
            return ['status' => 0, 'msg' => '设置失败!'];
        }
    }
    public function order()
    {
        $info = db('program');
        $data = input('post.');
        if ($info->update($data) !== false) {
            return $result = ['msg' => '操作成功！', 'url' => url('index'), 'code' => 1];
        } else {
            return $result = ['code' => 0, 'msg' => '操作失败！'];
        }
    }

    public function del()
    {
        $id = input('post.id');
        db('program')->where('id', $id)->delete();
        $result['code'] = 1;
        $result['msg'] = '删除成功!';
        $result['url'] = url('index');
        return $result;
    }

    public function delall()
    {
        $map['id'] = array('in', input('param.ids/a'));
        db('program')->where($map)->delete();
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('index');
        return $result;
    }

    public function log()
    {
        if (request()->isPost()) {
            //构建数组
            $data = input('post.');
            $data['create_time'] = time();
            $data['user_id'] = session('aid');
            $data['station'] = session('station');
            db('program_log')->insert($data);
            $result['code'] = 1;
            $result['msg'] = '添加成功!';
            $result['url'] = url('index');
            return $result;
        } else {
            $data = request()->param();
            $is_work = db('program_user')->where(['program_id' => $data['id'],'user_id' => session('aid')])->find();
//            if(!$is_work){
//
//            }
            $this->assign('title', '添加信息');
            $this->assign('info', 'null');
            $this->assign('program_id', $data['id']);
            return $this->fetch('log');
        }
    }
}