<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\controller\Init;
class Index extends Init
{
    public function index()
    {
    	$this->assign('aName',$this->Sadmin['admin_name']);
    	return $this->fetch();
    }
    public function welcome()
    {
    	$this->assign('aName',$this->Sadmin['admin_name']);
    	$this->assign('nowTime',date('Y-m-d H:i:s'));
    	return $this->fetch('index/welcome');
    }
}
