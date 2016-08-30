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
		$info['master_name'] = I('post.name');
		$info['master_pwd'] = md5(I('post.pass'));
		$result = M('Master')->where($info)->find();
		if($result){
			session('master_id',$result['master_id']);
			session('master_name',$result['master_name']);
			session('super',$result['super']);
			$this->redirect('Master/Index/index');
		}else{
			$this->error('用户名或者密码不正确',U('Master/Public/login'),3);
		}
	}

	/**
	 * 管理员列表，只有超级管理员有权限进入
	 */
	public function master_list(){
		if(session('super') == 1){
			$list = M('Master')->select();
			$this->list = $list;
			$this->display();
		}
	}

	/**
	 * 添加管理员
	 */
	public function add_master(){
		$this->display();
	}
	/**
	 * 处理添加逻辑
	 *
	 */
	public function deal_addMaster(){
		$Master = M('Master');
		$info['master_name'] = I('post.master_name');
		$existed = $Master->where($info)->find();
		// 用户名已存在
		if($existed){
			$this->error("用户名已存在,请重新设置");
		}
		$info['master_pwd'] = I('post.master_pwd');
		if($info['master_pwd'] == I('post.sure_pwd')){
			$result = $Master->data($info)->add();
			if($result){
				$this->success("添加成功",U('Master/Public/master_list'));
			}
			else{
				$this->error("添加失败，请稍后再试");
			}
		}
		else{
			$this->error("两次密码不相同，请重新输入");
		}
	}
	/**
	 * 删除管理员账号
	 *
	 */
	public function del_master(){
		$Master = M("Master");
		$info['master_id'] = I('get.id');

		$result = $Master->where($info)->delete();
		if($result){
			$this->success("删除成功");
		}
		else{
			$this->error("删除失败,请稍后重试");
		}
	}

	/**
	 * 修改密码
	 *
	 */
	public function modify_pwd(){
		$this->master_name = session('master_name');
		$this->super = session('super');
		$this->display();
	}

	/**
	 * 处理修改，密码逻辑
	 *
	 */
	public function deal_modify_pwd(){
		$Master = M('Master');
		$info['master_id'] = session('master_id');
		$info['master_pwd'] = md5(I('post.old_pwd'));
		$isRight = $Master->where($info)->find();
		if($isRight){
			if(I('post.new_pwd') == I('post.sure_pwd')){
				$info['master_pwd'] = md5(I('post.new_pwd'));
				$result = $Master->save($info);
				if ($result) {
					session(null);
					$this->success("修改成功，请重新登陆",U('Master/Public/login'));
				}
				else{
					$this->error("修改失败，请稍后再试");
				}
			}
			else{
				$this->error("两次密码不相同，请重新输入");
			}
		}
		else{
			$this->error("密码错误，请重新输入");
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
