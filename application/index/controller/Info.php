<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Layout;
use Db;
use think\facade\Request;
class Info extends Layout
{
    public function info($id)
    {
    	$ip = ip2long(Request::ip());
    	$time = time();
    	$ip_time = Db::name('ip_time')->where('ip',$ip)->where('aid',$id)->find();
    	if(!$ip_time) {
    		Db::name('ip_time')->insert(['ip'=>$ip,'time'=>$time,'aid'=>$id]);
    		Db::name('article')->where('article_id',$id)->setInc('article_pv');
    	} else {
    		if($time - $ip_time['time'] >= 300) {
    			Db::name('article')->where('article_id',$id)->setInc('article_pv');
    			Db::name('ip_time')->where('ip',$ip)->where('aid',$id)->update(['time'=>$time]);
    		}
    	}
		// Db::name('article')->where('article_id',$id)->setInc('article_pv');
    	$res = Db::name('article')->where('article_id',$id)->find();
    	$this->assign('article',$res);

    	//上下篇
    	$up = Db::name('article')->field('article_id,article_title')->where('article_id','<',$id)->order('article_id desc')->limit(1)->find();
    	$down = Db::name('article')->field('article_id,article_title')->where('article_id','>',$id)->limit(1)->find();
    	$this->assign('up',$up);
    	$this->assign('down',$down);

    	//相关文章
    	// $xgart = Db::name('article')->field('article_id,article_title')->where("article_title like '%{$res['article_title']}%' or article_content like '%{$res['article_content']}%'")->select();
    	// var_dump($xgart);die;
    	return $this->fetch();
    }
}
