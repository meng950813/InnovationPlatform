<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 教育培训
 */
class EduController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function edulist(){
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function edu(){
    	$this->display();
    }
}
?>