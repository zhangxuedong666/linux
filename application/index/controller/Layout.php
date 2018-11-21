<?php
namespace app\index\controller;
use think\Controller;
use Db;
class Layout extends Controller
{	
	protected function initialize()
    {
    	//导航分类
    	$navList = Db::name('variable_list')
			    	->field('vlist_name,vlist_desc')
			    	->where('variable_id',1)
			    	->order('vlist_prarm ASC')
			    	->select();
		$this->assign('navList',$navList);

		//标签云
    	$tagList = Db::name('tag')
			    	->select();
		$this->assign('tagList',$tagList);


		//点击排行
		$clickList = Db::name('article')->order('article_pv desc')->limit(10)->select();

		$this->assign('clickList',$clickList);
		//站长推荐
		
		$recommendList = Db::name('article')->where('article_recommend',1)->limit(10)->select();

		$this->assign('recommendList',$recommendList);

		//友情链接
    }

}
