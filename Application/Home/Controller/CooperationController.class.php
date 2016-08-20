<?php
namespace Home\Controller;
use Think\Controller;

/**
* 
*/
class CooperationController extends Controller{
	
	public function index(){
		$where['status'] = 1;
		if(!empty(I('get.type'))){
			switch (I('get.type')) {
				case 1:
					// 协同协会
					$where['type'] = 1;
					break;
				case 2:
					// 协同企业
					$where['type'] = 2;
					break;
				default:
					// 协同高校
					$where['type'] = 0;
					break;
			}
		}

		$Cooperation = M('Cooperation');

		$info = $Cooperation->where($where)->order('sort desc,click desc')->page(I('get.p',1).',20')->select();

		$count = $Cooperation->where($where)->count();
		$page = new \Think\Page($count,20);
		$show = $page->show();

		$this->assign('type',I('get.type'));
		$this->assign('list',$info);
		$this->assign('page',$show);
		$this->display();
	}

	public function addClick(){
		$where['id'] = I('post.id');
		$result = M('Cooperation')->where($where)->setInc('click');
		$this->ajaxReturn(['code'=>1]);
	}
}
?>