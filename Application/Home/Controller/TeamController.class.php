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
        echo "string";
        $list['info'] = 1;
        $this->assign("list",$list);
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