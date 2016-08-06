<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 注册
 */
class SignupController extends Controller {
	/**
	 * 展示注册页面
	 *
	 */
	public function signup()	{
		$this->display();
	}

	/**
	* 判断输入的姓名是否已被注册
	*/
	public function checkName(){
		// 获取用户名
		$where['username'] = I('post.username');
		// 在注册表中查找姓名
		$result = M('Login')->where($where)->find();
		// 查找有结果 -> 该用户名已被注册
		if($result){
			$this->ajaxReturn(['code'=>1]);
		}else{
			$this->ajaxReturn(['code'=>0]);
		}
	}
	/**
	*显示专家注册页面
	*/
	public function signup_expert(){
		$this->display();
	}

	/**
	*处理专家注册页面信息
	*/
	public function do_signup_expert(){
		// 获取注册数据
		$loginInfo['username'] = I('post.username');
		// 密码加密
		$loginInfo['password'] = md5($_POST['pwd']);
		/* 	其中：I方法与$_POST/$_GET效果相同；
			区别在于I方法可以设置缺省值：I('get.name','缺省值')；
		*/
		$loginInfo['type'] = 1;
		$loginInfo['realname'] = I('post.realname');
		if(!empty($_FILES)){//有图上传
			// 返回值不为null -> 上传成功
			if(($logoPath = uploadPicture($_FILES,'user_logo'))){
				$loginInfo['logo'] = $logoPath;
			}
		}
		$loginInfo['mobile_phone'] = I('post.phone');
		$loginInfo['email'] = I('post.email');
		$result = M('Login')->add($loginInfo);
		/*
			若信息在login表中添加不成功，则没有必要继续在expert表中添加
			因为expert中的uid字段是login表中id的外键
		*/
		if($result){
			session('uid',$expertInfo['uid']);
			session('type',2);
			session('realname',$loginInfo['realname']);
			$where['username'] = $loginInfo['username'];
			$expertInfo['uid'] = M('Login')->where($where)->getField('id');
			$expertInfo['level'] = getJobType(I('post.level'));
			// 复选框，以数组形式存储值
			$research = I("post.research");
			$expertInfo['research'] = getResearchType($research);
			$expertInfo['work_college'] = I('post.college');
			$expertInfo['jobname'] = I('post.jobname');
			$expertInfo['info'] = I('post.info');
			$result = M('ExpertUser')->add($expertInfo);
			$this->success("欢迎你,".$loginInfo['realname'].',请完善个人信息',U('Personal/modify'));
		}else{
			$this->error("很可惜，注册失败了",U('Signup/signup'));
		}
		$loginInfo['identify'] = I('post.identify');
	}

	/**
	 * 显示公司注册的页面
	 *
	 * @return [type] [description]
	 */
	public function signup_company(){
		$this->display();
	}

	/**
	 * 处理企业注册的信息
	 *
	 * @return [type] [description]
	 */
	public function do_signup_company(){
		// type =1
	}

	/**
	 * 显示个人用户的注册页面
	 *
	 * @return [type] [description]
	 */
	public function signup_person(){
		$this->display();
	}

	/**
	 * 处理个人用户的注册信息
	 *
	 * @return [type] [description]
	 */
	public function do_signup_person(){
		// type=0
	}

	/**
	 * 找回忘记密码第一步:显示页面
	 *
	 * @return [type] [description]
	 */
	public function forgetPwd_step1(){
		$this->display();
	}

	/**
	 * 找回忘记密码第二步:显示页面
	 *
	 * @return [type] [description]
	 */
	public function forgetPwd_step2(){
		$this->assign("username",I('get.username',null));
		$this->display();
	}

	/**
	 * 找回忘记密码第三步:显示页面
	 *
	 * @return [type] [description]
	 */
	public function forgetPwd_step3(){
		$this->assign("username",I('get.username',null));
		$this->display();
	}


	/**
	 * 找回忘记密码第一步:处理数据
	 *
	 * @return [type] [description]
	 */
	public function do_forgetPwd_step1(){
		$info['username'] = I('get.username');
		$info['realname'] = I('get.realname');
		$result = M('Login')->where($info)->find();
		// 找到用户
		if($result){
			$this->redirect('Hoem/Signup/forgetPwd_step2?un='.$info['username']);
		}else{
			$this->error("输入信息不正确",U('Signup/forgetPwd_step1'));
		}
	}

	/**
	 * 找回忘记密码第二步:处理数据
	 *
	 * @return [type] [description]
	 */
	public function do_forgetPwd_step2(){
		// 若没有来自第一步的数据，则非正常流程，不予处理
		if(I('get.username',null)){
			$where['username']=I('get.username');
			$where['mobile_phone'] = I('get.phone');
			$where['email'] = I('get.email');
			$result = M('Login')->where($where)->find();
			if($result){
				$this->redirect('Hoem/Signup/forgetPwd_step3?un='.$where['username']);
			}
			else{
				$this->error("输入信息不正确",U('Signup/forgetPwd_step2',array('username'=>$where['username'])));
			}
		}
	}

	/**
	 * 找回忘记密码第三步:处理数据
	 *
	 * @return [type] [description]
	 */
	public function do_forgetPwd_step3(){
		if(I('post.username',null)){
			$where['username'] = I('post.username');
			$newPwd = I('post.newPwd');
			$sure_pwd = I('post.sure_pwd');
			if($newPwd == $sure_pwd){
				$info['password'] = md5($newPwd);

				//更新数据
				$result = M('Login')->where($where)->update($info); 
				if($result){
					$this->success("修改成功，请登录",U('Public/login'));
				}
				else{
					$this->error('修改失败，请重试',U('Signup/forgetPwd_step3',array('username'=>$where['username'])));
				}

			}
			else{
				$this->error('两次密码不相同',U('Signup/forgetPwd_step3',array('username'=>$where['username'])));
			}
			
		}
	}
}

?>