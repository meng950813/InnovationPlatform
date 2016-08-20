<?php
namespace Master\Controller;
use Lib\MasterController;

class EduController extends MasterController {
	public function news_list(){

		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['edu_id'] = I('get.id');

			$result = M('EduTrain')->delete($where);
			if($result){
				$this->success('删除成功');
			}
			else{
				$this->error('删除失败，请稍后再试');
			}
		}
		else{
			$this->error('请选择删除对象');
		}
	}

	public function add_modify(){
		if(I('get.id')){
			$where['news_id'] = I('get.id');
			$info = M('News')->where($where)->find();
			switch ($info['type']) {
				case 1:
					$info['type_name'] = '职业资格教育';
					break;
				case 2:
					$info['type_name'] = '技能培训';
					break;
				default:
					$info['type_name'] = '学历教育';
					break;
			}
			$this->info = $info;
		}
		
		$this->display();
	}

	public function deal_info(){
		
	}
}
