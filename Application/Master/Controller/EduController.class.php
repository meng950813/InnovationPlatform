<?php
namespace Master\Controller;
use Lib\MasterController;
class EduController extends MasterController {
	public function edu_list(){
		$where;
		if(I('get.type')){
			switch (I('get.type')) {
				case 1:
					$where['type'] = 1;
					break;
				case 2:
					$where['type'] = 2;
					break;
				default:
					$where['type'] = 0;
					break;
			}
		}

		$Edu = M('EduTrain'); // 实例化对象
		$num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $Edu->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Lib\Page($count,$num);//化分页类入总记录数和每页显示的记录数
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();// 分页显示输出

		$list = $Edu->where($where)->page(I('get.p','1').",$num")->order('sort desc')->select();
		
		$this->list = $list;// 赋值数据集
		$this->page = $show;// 赋值分页输出

		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['edu_id'] = I('get.id');
			
			$result = M('EduTrain')->where($where)->delete();
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
			$where['edu_id'] = I('get.id');
			$info = M('EduTrain')->where($where)->find();
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

	public function deal_edu(){
		// 获取表单全部信息，使用这种方法要保证表单中的变量名与数据库中的字段名一致
		$edu=$_POST;
		// 获取当前日期
		$edu['publish_time'] = date('Y-m-d');
		// 传值有id ，修改数据
		if(!empty(I('post.edu_id'))){
			$result = M('EduTrain')->data($edu)->save();
			if($result){
				$this->success("修改成功",U('Master/Edu/edu_list/type/'.$edu['type']));
			}
			else{
				$this->error("修改失败，请稍后重试");
			}
		}
		// 添加数据
		else{
			if(!empty($edu['type']) || $edu['type'] == 0){
				$result = M('EduTrain')->data($edu)->add();
				if($result){
					($edu['type'] == 0)&&($edu['type'] =3 );
					$this->success("发布成功",U('Master/Edu/edu_list/type/'.$edu['type']));
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
