<?php
namespace app\index\controller;
use think\Controller;
class Other extends Controller
{
    public function about()
    {
    	return $this->fetch();
    }
}
