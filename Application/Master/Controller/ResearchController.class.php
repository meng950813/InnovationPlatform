<?php
namespace Master\Controller;
use Lib\MasterController;

class ResearchController extends MasterController {
	public function research_list(){

		$ResearchResult = M('ResearchResult'); // 实例化对象
		$num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $ResearchResult->count();// 查询满足要求的总记录数
		$Page = new \Lib\Page($count,$num);//化分页类入总记录数和每页显示的记录数
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();// 分页显示输出

		$list = $ResearchResult->page(I('get.p','1').",$num")->order('sort desc')->select();
		
		$this->list = $list;// 赋值数据集
		$this->page = $show;// 赋值分页输出

		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['result_id'] = I('get.id');
			$result = M('ResearchResult')->where($where)->delete();
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

}
