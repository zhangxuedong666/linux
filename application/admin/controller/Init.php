<?php
namespace app\admin\controller;
use think\Controller;
class Init extends Controller
{
	protected $Sadmin;
	protected function initialize()
    {
    	$this->Sadmin = session('Sadmin','','admin');
    	if(!$this->Sadmin) {
    		return $this->redirect('admin/login/index');
    	}
    }
}
