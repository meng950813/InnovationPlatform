<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 最新资讯
 */
class NewsController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function newslist(){
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function newscontent(){
    	$this->display();
    }
}
?>