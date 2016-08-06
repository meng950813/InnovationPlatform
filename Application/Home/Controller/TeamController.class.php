<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 专家团队
 */
class TeamController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function teamlist(){
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function team(){
    	$this->display();
    }

    /**
     * 创建新团队
     *
     * @return [type] [description]
     */
    public function add(){
        
    }
}
?>