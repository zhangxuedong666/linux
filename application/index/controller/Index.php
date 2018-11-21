<?php
namespace app\index\controller;
use app\index\controller\Layout;
use Db;
class Index extends Layout
{

    public function index()
    {
    	$res = Db::name('article')->where('article_isindex',1)->limit(10)->select();
    	

    	if($res) {
    		foreach($res as &$v) {
	    		$tags = Db::name('tag')
						->field('t.tag_id,t.tag_name')
						->alias('t')
						->join('bk_article_tag a','a.tid = t.tag_id')
						->where('a.aid',$v['article_id'])
						->select();
				if($tags) {
					$tags = array_column($tags, 'tag_name','tag_id');
					$v['tags'] = $tags;
				}	
    		}
    	}
    	// var_dump($res);die;
    	$this->assign('artList',$res);
    	return $this->fetch();
    }
    public function xlist()
    {

    	$res = Db::name('article')->order('article_pv desc')->paginate(8)->each(function($item, $key){
    		$tags = Db::name('tag')
					->field('t.tag_id,t.tag_name')
					->alias('t')
					->join('bk_article_tag a','a.tid = t.tag_id')
					->where('a.aid',$item['article_id'])
					->select();
			if($tags) {
				$tags = array_column($tags, 'tag_name','tag_id');
				$item['tags'] = $tags;
			}	
			return $item;
		});
    	$page = $res->render();
    	$this->assign('artList',$res);
    	$this->assign('page',$page);
    	return $this->fetch('list');
    }

    public function tag($id) 
    {
    	 $tags = Db::name('article_tag')->field('aid')->where('tid',$id)->select();
    	 if($tags) {
    	 	$tags = implode(',',array_column($tags,'aid'));
    	 }
    	 $res = Db::name('article')->order('article_pv desc')->where('article_id','in',$tags)->paginate(8)->each(function($item, $key){
    		$tags = Db::name('tag')
					->field('t.tag_id,t.tag_name')
					->alias('t')
					->join('bk_article_tag a','a.tid = t.tag_id')
					->where('a.aid',$item['article_id'])
					->select();
			if($tags) {
				$tags = array_column($tags, 'tag_name','tag_id');
				$item['tags'] = $tags;
			}	
			return $item;
		});
    	$page = $res->render();
    	$this->assign('artList',$res);
    	$this->assign('page',$page);
    	return $this->fetch('list');
    }

        //搜索
    public function search()
    {
    	$request = Input('post.');
    	$res = Db::name('article')->order('article_pv desc')->where("article_title like '%{$request['keyword']}%' or article_content like '%{$request['keyword']}%'")->paginate(8)->each(function($item, $key){
    		$tags = Db::name('tag')
					->field('t.tag_id,t.tag_name')
					->alias('t')
					->join('bk_article_tag a','a.tid = t.tag_id')
					->where('a.aid',$item['article_id'])
					->select();
			if($tags) {
				$tags = array_column($tags, 'tag_name','tag_id');
				$item['tags'] = $tags;
			}	
			return $item;
		});
    	$page = $res->render();
    	$this->assign('artList',$res);
    	$this->assign('page',$page);
    	$this->assign('keyword',$request['keyword']);
    	return $this->fetch('list');

    }
}
