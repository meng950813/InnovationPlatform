<?php
namespace Master\Controller;
use Lib\MasterController;
class CooperationController extends MasterController {
	public function coo_list(){
		$where;
		if(I('get.type')){
			switch (I('get.type')) {
				case 1:
					// 行业协会
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
        $num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $Cooperation->where($where)->count();
		$Page = new \Lib\Page($count,$num);
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();
		$list = $Cooperation->where($where)->page(I('get.p','1').",$num")->order('sort desc')->select();
		
		$this->list = $list;// 赋值数据集
		$this->page = $show;// 赋值分页输出
		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['id'] = I('get.id');

			$result = M('Cooperation')->delete($where);
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
			$where['id'] = I('get.id');
			$info = M('Cooperation')->where($where)->find();
			switch ($info['type']) {
				case 1:
					$info['type_name'] = '行业协会';
					break;
				case 2:
					$info['type_name'] = '协同企业';
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
		// 获取表单全部信息，使用这种方法要保证表单中的变量名与数据库中的字段名一致
		$cooperation=$_POST;
		// 传值有id ，修改数据
		if(!empty(I('id'))){
			$result = M('Cooperation')->data($cooperation)->save();
			if($result){
				$this->success("修改成功",U('Master/Cooperation/coo_list/type/'.$cooperation['type']));
			}
			else{
				$this->error("修改失败，请稍后重试");
			}
		}
		// 添加数据  有没有发现问题在哪 仔细看看 对 不是我写的。。。试试看
		else{
			if(!empty($cooperation['type'])){
				$result = M('Cooperation')->data($cooperation)->add();
				if($result){
					$this->success("发布成功",U('Master/Cooperation/coo_list/type/'.$cooperation['type']));
				}
				else{
					$this->error("发布失败，请稍后重试");
				}
			}
			else{
				$this->error("请选择类型");
			}
			
		}
	}
}
