<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Variable as V;
use app\admin\model\VariableList as VL;
use think\Db;
use app\admin\controller\Init;
class Variable extends Init
{	
	public $v;
	public $vl;
	protected function initialize()
    {
        $this->v = new V();
        $this->vl = new VL();
    }
    public function index()
    {
    	$request = Input('get.');
    	$datatype = array();
    	if(isset($request['keyword']) && $request['keyword']!=='') {
    		$keyword = "variable_name like '%{$request['keyword']}%'";
    		$datatype['keyword'] = $request['keyword'];
    	} else {
    		$datatype['keyword'] = '';
    		$keyword = true;
    	}
    	$list = $this->v->where($keyword)->order('variable_id desc')->paginate(10);
    	$page = $list->render();
    	$count = $list->total();
    	$this->assign('dataList',$list);
    	$this->assign('page',$page);
    	$this->assign('datatype',$datatype);
    	$this->assign('count',$count);
    	return $this->fetch('variable-list');
    }
    public function add()
    {
    	$request = Input('post.');
		if($this->v->where('variable_name',$request['variable_name'])->find()) {
			return json(array('error'=>1,'errortip'=>'名称已存在','result'=>array()));
		}
		$res = $this->v->save($request);
		if($res) {
			return json(array('error'=>0,'errortip'=>'添加成功','result'=>array()));
		} else {
			return json(array('error'=>1,'errortip'=>'添加失败','result'=>array()));
		}
    }
    public function edit()
    {
    	$request = Input('post.');
    	$data = array();
    	$insertData[$request['field']] = $request['value'];
		// save方法第二个参数为更新条件
		$res = $this->v->save($insertData,['variable_id' => $request['id']]);
		if($res) {
			return json(array('error'=>0,'errortip'=>'修改配置名称成功','result'=>date('Y-m-d H:i:s')));
		} else {
			return json(array('error'=>1,'errortip'=>'修改配置名称失败','result'=>array()));
		}
	}

	
	public function del()
	{
		$request = Input('get.');
		$res = $this->v->where('variable_id',$request['id'])->delete();
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
		$res = $this->v->where('variable_id','in',$ids)->delete();
		if($res) {
			return json(array('error'=>0,'errortip'=>'批量删除成功','result'=>array()));
		} else {
			return json(array('error'=>1,'errortip'=>'批量删除失败','result'=>array()));
		}
	}

	public function vList($id)
	{
		$dataList = Db::name('variable')
			->field('v.variable_id,v.variable_name,l.vlist_name,l.vlist_desc,l.vlist_id')
			->alias('v')
			->join('bk_variable_list l','v.variable_id = l.variable_id')
			->where('v.variable_id',$id)
			->select();
		$this->assign('dataList',$dataList);
		$this->assign('id',$id);
		$vname = Db::name('variable')->field('variable_name')->where('variable_id',$id)->find();
		$this->assign('vname',$vname['variable_name']);
		return $this->fetch('vlist-add');
	} 

	public function addvList()
	{
		$request = Input('post.');
		$insertData = array();
		if($request['namesArr']) {
			foreach($request['namesArr'] as $k=>$v) {
				$insertData[$k]['variable_id'] = $request['id'];
				$insertData[$k]['vlist_name'] = $v;
				$insertData[$k]['vlist_desc'] = $request['descsArr'][$k];
				$insertData[$k]['vlist_prarm'] = $k;
			}
		}
		Db::name('variable_list')->where('variable_id',$request['id'])->delete();
		$res = Db::name('variable_list')->insertAll($insertData);
		if($res) {
			return json(array('error'=>0,'errortip'=>'保存成功','result'=>array()));
		} else {
			return json(array('error'=>1,'errortip'=>'保存失败','result'=>array()));
		}
	}

	
}
