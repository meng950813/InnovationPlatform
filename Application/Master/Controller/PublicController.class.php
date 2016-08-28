<?php
namespace Master\Controller;
use Think\Controller;
class PublicController extends Controller {
	public function uploadicon($path){
		C('SHOW_PAGE_TRACE',false);
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize = 3145728 ;// 设置附件上传大小
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath = $path; // 设置附件上传目录
		$upload->subName = array('date','Ymd');
		// 上传文件
		$info = $upload->upload();

		if(!$info) {
			// 上传错误提示错误信息
			$pic['status'] = 1;
			$pic['errmsg'] = $upload->getError();
		}else{
			// 上传成功
			$pic['status'] = 0;
			$pic['path'] = 'Uploads/'.$info['icon']['savepath'].$info['icon']['savename'];
		}
		echo json_encode($pic);
	}
	public function login(){
		$this->display();
	}
	public function dologin(){
		$name = I('post.name');
		$pass = I('post.pass');
		if($name == 'master' && $pass == '123'){
			session('power','admin');
			$this->redirect('Master/Index/index');
		}else{
			$this->error('用户名或者密码不正确',U('Master/Public/login'),3);
		}
	}

	public function setStatus(){
		// 设置news表中记录的可用性
		switch (I('get.type')) {

			case 'news':
				$where['news_id'] = I('get.id'); 
				$where['status'] = (I('get.status')+1)%2;
				$result = M("News")->data($where)->save();
				break;

			case 'res':
				$where['result_id'] = I('get.id'); 
				$where['status'] = (I('get.status')+1)%2;
				$result = M("ResearchResult")->data($where)->save();
				break;

			case 'dem':
				$where['demand_id'] = I('get.id'); 
				$where['status'] = (I('get.status')+1)%2;
				$result = M("Demand")->data($where)->save();
				break;

			case 'trian':
				$where['edu_id'] = I('get.id'); 
				$where['status'] = (I('get.status')+1)%2;
				$result = M("EduTrain")->data($where)->save();
				break;

			case 'coop':
				$where['id'] = I('get.id'); 
				$where['status'] = (I('get.status')+1)%2;
				$result = M("Cooperation")->data($where)->save();
				break;
			default:
				# code...
				break;
		}
		if($result){
			$this->success("修改成功");
		}
		else{
			$this->error("修改失败");
		}
	}
}
?>
