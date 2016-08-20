<?php
namespace Master\Controller;
use Lib\MasterController;

class CityController extends MasterController {

	public function city_list(){

		$City = M('City'); // 实例化对象
		$num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $City->count();// 查询满足要求的总记录数

		$Page = new \Lib\Page($count,$num);//化分页类入总记录数和每页显示的记录数
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();// 分页显示输出

		$list = $City->select();
		$this->list = $list;
		$this->page = $show;
		$this->display();
	}

	//显示添加/修改页面 
	public function add_city(){
		// 获取到城市id,表示修改
		if(I('get.id')){
			$where['id'] = I('get.id');
			$info = M('City')->where($where)->find();
			$this->info = $info;
		}
		$this->display();
	}

	// 处理添加/修改信息
	public function deal_info(){
		// 获取表单全部信息，使用这种方法要保证表单中的变量名与数据库中的字段名一致
		$info = $_POST;
		// 表单中有id信息，表示修改
		if(!empty($info['id'])){
			$result = M('City')->data($info)->save();
			if($result){
				$this->success("修改成功",U('Master/City/city_list'));
			}
			else{
				$this->error("修改失败，请稍后再试");
			}
		}
		else{
			$result = M('City')->data($info)->add();
			if($result){
				$this->success("添加成功",U('Master/City/city_list'));
			}
			else{
				$this->error("添加失败，请稍后再试");
			}
		}
	}
	public function del_city(){
		$where['id'] = I('get.id');
		$result = M('City')->where($where)->delete();
		if($result){
			$this->success("删除成功");
		}
		else{
			$this->error("删除失败，请稍后再试");
		}
	}
}
?>
