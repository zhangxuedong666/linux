<?php
namespace app\admin\controller;
use think\Controller;
use Db;
class Article extends Controller
{
    public function index()
    {
        $request = Input('get.');
        $datawhere = array();
        $datatype = array();
        if(isset($request['keyword']) && $request['keyword']!=='') {
            $datawhere[] = [
                ['article_title', 'like', '%'.$request['keyword'].'%']
            ];
            $datatype['keyword'] = $request['keyword'];
        }
        $list = Db::name('article')->where($datawhere)->order('article_id desc')->paginate(10);
        $page = $list->render();
        $count = $list->total();
        $this->assign('dataList',$list);
        $this->assign('page',$page);
        $this->assign('count',$count);

        $cateList = Db::name('variable_list')->field('vlist_prarm,vlist_name')->where('variable_id',1)->select();
        if($cateList) {
            $cateList = array_column($cateList,'vlist_name','vlist_prarm');
        }
        $this->assign('cateList',$cateList);
        $this->assign('datatype',$datatype);
        return $this->fetch('art-list');
    }
    public function add()
    {
    	$cateList = Db::name('variable_list')->where('variable_id','1')->select();
    	$this->assign('cateList',$cateList);

        $tagList = Db::name('tag')->select();
        $this->assign('tagList',$tagList);
    	return $this->fetch('art-add');
    }
    public function addAction()
    {
        $request = Input('post.');
        $a_last_id = 0;
        $res_t = 0;
        Db::transaction(function () use($request,&$a_last_id,&$res_t){
            $article = array();
            $article['article_title'] = $request['article_title'];
            $article['article_desc'] = $request['article_desc'];
            $article['category_id'] = $request['category_id'];
            $article['article_content'] = $request['article_content'];
            $article['article_updatetime'] = time();
            $article['article_addtime'] = time();
            $a_last_id = Db::name('article')->insertGetId($article);
            $tagArr = array();
            array_walk($request['curArr'],function($v,$k) use(&$tagArr,$a_last_id) {
                $tagArr[$k]['aid'] = $a_last_id;
                $tagArr[$k]['tid'] = $v;
            });
            $res_t = Db::name('article_tag')->insertAll($tagArr);
        });
        if($res_t && $a_last_id) {
            return json(array('error'=>0,'errortip'=>'添加文章成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'添加文章失败','result'=>array()));
        }
    }
    public function batchDel()
    {
        $request = Input('get.');
        if(!isset($request['data']) || $request['data']=='') {
            return json(array('error'=>0,'errortip'=>'请选择要删除的数据','result'=>array()));
        }
        $ids = implode(',',$request['data']);
        $res = Db::name('article')->where('article_id','in',$ids)->delete();
        if($res) {
            return json(array('error'=>0,'errortip'=>'批量删除成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'批量删除失败','result'=>array()));
        }
    }

    public function del()
    {
        $request = Input('get.');
        $res = Db::name('article')->where('article_id',$request['id'])->delete();
        if($res) {
            return json(array('error'=>0,'errortip'=>'删除成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'删除失败','result'=>array()));
        }
    }
    public function edit($id)
    {
        $request = Input('get.');
        $cateList = Db::name('variable_list')->where('variable_id','1')->select();
        $this->assign('cateList',$cateList);
        $tagList = Db::name('tag')->select();
        $this->assign('tagList',$tagList);

        $curTagList = Db::name('article_tag')->field('tid')->where('aid',$id)->select();
        if($curTagList) {
            $curTagList = array_column($curTagList,'tid');
        }
        $this->assign('curTagList',$curTagList);
        $artOne = Db::name('article')->where('article_id',$id)->find();
        $this->assign('artOne',$artOne);
        return $this->fetch('art-edit');
    }
    public function editAction($id)
    {
        $request = Input('post.');

        $article = array();
        $article['article_title'] = $request['article_title'];
        $article['category_id'] = $request['category_id'];
        $article['article_content'] = $request['article_content'];
        $article['article_updatetime'] = time();
        $a_last_id = Db::name('article')->where('article_id', $id)->update($article);

        $at_del_t = Db::name('article_tag')->where('aid',$id)->delete();
        array_walk($request['curArr'],function($v,$k) use(&$tagArr,$id) {
            $tagArr[$k]['aid'] = $id;
            $tagArr[$k]['tid'] = $v;
        });
        $res_t = Db::name('article_tag')->insertAll($tagArr);
        if($res_t && $a_last_id && $at_del_t) {
            return json(array('error'=>0,'errortip'=>'编辑成功','result'=>array()));
        } else {
            return json(array('error'=>1,'errortip'=>'编辑失败','result'=>array()));
        }
    }
}
