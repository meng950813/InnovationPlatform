<?php
namespace Home\Controller;
use Think\Controller;

/**
* create by cm
* 首页
*/
class IndexController extends Controller {
    public function index(){
    	$where['status'] = 1;
    	$newsPictureInfo = D('newsPictureInfoView')->where($where)->order('sort desc')->limit(5)->field('news_id,title,picture')->select();
    	/*var_dump($newsPictureInfo);*/

    	$newsInfo = M("News")->order('sort desc')->limit(10)->field('news_id,title')->select();

    	// var_dump($newsInfo);
    	$this->assign("newsPicture",$newsPictureInfo);
    	$this->assign("newsInfo",$newsInfo);

    	$this->display();
    }
}
?>