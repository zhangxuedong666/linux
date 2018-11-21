<?php
namespace app\admin\controller;
use think\Controller;
use Db;
class Login extends Controller
{
    public function index()
    {
    	return $this->fetch('login');
    }
    public function login()
    {
    	$request = Input('post.');
    	$name = $request['admin_name'];
    	$pwd = md5($request['admin_pwd']);
    	$res = Db::name('admin')->where('admin_name',$name)->where('admin_pwd',$pwd)->find();
        $byname = Db::name('admin')->field('admin_name')->where('admin_name',$name)->find();
    	if(!$byname) {
    		return json(array('error'=>1,'errortip'=>'管理员账号不存在','result'=>array()));
        } else {
            if(!$res) {
                return json(array('error'=>1,'errortip'=>'账号密码错误','result'=>array()));
            } else {
                $lastIp = ip2long(request()->ip());
                Db::name('admin')->where('admin_name',$name)->where('admin_pwd',$pwd)->update(array('last_ip'=>$lastIp,'last_time'=>time()));
                session('Sadmin',$res,'admin');
                return json(array('error'=>0,'errortip'=>'登陆成功','result'=>array()));
            }
    	}
    }

    public function exitLogin()
    {
        if(!session(null, 'admin')) {
            return $this->redirect('/admin/login/index');
        }
    }
}
