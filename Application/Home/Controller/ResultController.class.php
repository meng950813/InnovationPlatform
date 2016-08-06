<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 科研成果
 */
class ResultController extends Controller {

	/**
	 * 科技成果列表页
	 *
	 * @return [type] [description]
	 */
    public function resultlist(){
    	$this->display();
    }

    /**
     * 详情页
     *
     * @return [type] [description]
     */
    public function result(){
    	$this->display();
    }

    /**
     * 科技成果添加页
     *
     * @return [type] [description]
     */
    public function add(){
        $this->display();
    }

    /**
     * 添加处理函数
     *
     * @return [type] [description]
     */
    public function addResearch(){
        
    }
}
?>