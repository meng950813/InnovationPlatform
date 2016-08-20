<?php
namespace Master\Controller;
use Lib\MasterController;

class CooperationController extends MasterController {
	public function coo_list(){

		$this->display();
	}

	public function del(){
		
	}

	public function add_modify(){
		if(I('get.id')){
			$where['id'] = I('get.id');
			$info = M('Cooperation')->where($where)->find();
			switch ($info['type']) {
				case 1:
					$info['type_name'] = '协同企业';
					break;
				case 2:
					$info['type_name'] = '行业协会';
					break;
				default:
					$info['type_name'] = '协同高校';
					break;
			}
			$this->info = $info;
		}
		
		$this->display();
	}

	// 处理表单数据
	public function deal_cooperation(){
		
	}
}
