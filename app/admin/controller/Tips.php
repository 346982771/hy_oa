<?php

namespace app\admin\controller;

use think\Db;
use think\Request;
use think\Controller;
use app\admin\controller\Menu;
class Tips extends Common
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
            $list = db('tips')
                ->where('title', 'like', "%" . $key . "%")
                ->where(['user_id' => session('aid')]);

            $list = $list->order('create_time desc')
                ->paginate(array('list_rows' => $pageSize, 'page' => $page))
                ->toArray();
            foreach ($list['data'] as $k => $v) {
                $list['data'][$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            }
            return $result = ['code' => 0, 'msg' => '获取成功!', 'data' => $list['data'], 'count' => $list['total'], 'rel' => 1];
        }

        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            //构建数组
            $data = input('post.');
            $data['create_time'] = time();
            $data['user_id'] = session('aid');
            db('tips')->insert($data);
            $result['code'] = 1;
            $result['msg'] = '添加成功!';
            $result['url'] = url('index');
            return $result;
        } else {

            $this->assign('title', '添加信息');
            $this->assign('info', 'null');
            return $this->fetch('form');
        }
    }

    public function edit()
    {
        if (request()->isPost()) {
            $data = request()->param();
            db('tips')->where('id', $data['id'])->update($data);
            $result['code'] = 1;
            $result['msg'] = '修改成功!';
            $result['url'] = url('index');
            return $result;
        } else {
            $id = input('id');
            $adInfo = db('tips')->where(['id' => $id])->find();
            $this->assign('info', json_encode($adInfo, true));
            $this->assign('adInfo', $adInfo);
            $this->assign('title', lang('edit') . '信息');

            return $this->fetch('form');
        }
    }

    public function detail()
    {
        $id = $this->request->param('id');

        $info = db('tips')
            ->where('id', $id)
            ->field('content')
            ->find();
        $this->assign('info', $info);
        return $this->fetch('');
    }
    public function recommendation()
    {
        $id = input('post.id');
        $recommendation = input('post.recommendation');
        if (db('tips')->where('id=' . $id)->update(['recommendation' => $recommendation]) !== false) {
            return ['status' => 1, 'msg' => '设置成功!'];
        } else {
            return ['status' => 0, 'msg' => '设置失败!'];
        }
    }
    //设置状态
    public function editHide()
    {
        $id = input('post.id');
        $hide = input('post.hide');
        if (db('tips')->where('id=' . $id)->update(['hide' => $hide]) !== false) {
            return ['status' => 1, 'msg' => '设置成功!'];
        } else {
            return ['status' => 0, 'msg' => '设置失败!'];
        }
    }
    public function order()
    {
        $info = db('tips');
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
        db('tips')->where('id', $id)->delete();
        $result['code'] = 1;
        $result['msg'] = '删除成功!';
        $result['url'] = url('index');
        return $result;
    }

    public function delall()
    {
        $map['id'] = array('in', input('param.ids/a'));
        db('tips')->where($map)->delete();
        $result['msg'] = '删除成功！';
        $result['code'] = 1;
        $result['url'] = url('index');
        return $result;
    }

}