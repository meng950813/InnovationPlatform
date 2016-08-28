<?php
namespace Master\Controller;
use Lib\MasterController;

class NewsController extends MasterController {
	public function news_list(){

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

		$News = M('News'); // 实例化对象
		$num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $News->where($where)->count();// 查询满足要求的总记录数
		$Page = new \Lib\Page($count,$num);//化分页类入总记录数和每页显示的记录数
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();// 分页显示输出

		$list = $News->where($where)->page(I('get.p','1').",$num")->order('sort desc')->select();
		
		$this->list = $list;// 赋值数据集
		$this->page = $show;// 赋值分页输出

		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['news_id'] = I('get.id');

			$result = M('News')->delete($where);
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
					$info['type_name'] = '市场动态';
					break;
				case 2:
					$info['type_name'] = '业内资讯';
					break;
				default:
					$info['type_name'] = '政策法规';
					break;
			}
			$this->info = $info;
		}
		
		$this->display();
	}

	public function deal_news(){
		// 获取表单全部信息，使用这种方法要保证表单中的变量名与数据库中的字段名一致
		$news=$_POST;
		// 获取当前日期
		$news['publish_time'] = date('Y-m-d');
		// 传值有id ，修改数据
		if(!empty(I('post.news_id'))){
			$result = M('News')->data($news)->save();
			if($result){
				$this->success("修改成功",U('Master/News/news_list/type/'.$news['type']));
			}
			else{
				$this->error("修改失败，请稍后重试");
			}
		}
		// 添加数据
		else{
			if(!empty($news['type'])){
				$result = M('News')->data($news)->add();
				if($result){
					$this->success("发布成功",U('Master/News/news_list/type/'.$news['type']));
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
