<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\controller\Init;
use Db;
class Tag extends Init
{
    public function index()
    {
        $list = Db::name('tag')->order('tag_id desc')->paginate(10);
        $page = $list->render();
        $count = $list->total();
        $this->assign('dataList',$list);
        $this->assign('page',$page);
        $this->assign('count',$count);
    	return $this->fetch('tag-list');
    }
    public function add()
    {
        $request = Input('post.');
        if(Db::name('tag')->where('tag_name',$request['tag_name'])->find()) {
            return json(array('error'=>1,'errortip'=>'名称已存在','result'=>array()));
        }
        $res = Db::name('tag')->insert($request);
        if($res) {
            return json(array('error'=>0,'errortip'=>'添加成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'添加失败','result'=>array()));
        }
    }

    public function del()
    {
        $request = Input('get.');
        $res = Db::name('tag')->where('tag_id',$request['id'])->delete();
        if($res) {
            return json(array('error'=>0,'errortip'=>'删除成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'删除失败','result'=>array()));
        }
    }

    public function batchDel()
    {
        $request = Input('get.');
        if(!isset($request['data']) || $request['data']=='') {
            return json(array('error'=>0,'errortip'=>'请选择要删除的数据','result'=>array()));
        }
        $ids = implode(',',$request['data']);
        $res = Db::name('tag')->where('tag_id','in',$ids)->delete();
        if($res) {
            return json(array('error'=>0,'errortip'=>'批量删除成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'批量删除失败','result'=>array()));
        }
    }

    public function edit()
    {
        $request = Input('post.');
        $data = array();
        $insertData[$request['field']] = $request['value'];
        // save方法第二个参数为更新条件
        // $res = Db::->save($insertData,['tag_id' => $request['id']]);
        $res = Db::name('tag')->where('tag_id', $request['id'])->update($insertData);
        if($res) {
            return json(array('error'=>0,'errortip'=>'修改配置名称成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'修改配置名称失败','result'=>array()));
        }
    }
}
