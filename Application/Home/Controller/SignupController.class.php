<?php
namespace Home\Controller;
use Think\Controller;


/**
 * create by cm
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
		$loginInfo['mobile_phone'] = I('post.mobile_phone');
		$loginInfo['email'] = I('post.email');
		$result = M('Login')->data($loginInfo)->add();
		/*
			若信息在login表中添加不成功，则没有必要继续在expert表中添加
			因为expert中的uid字段是login表中id的外键
		*/
		if($result){
			$where['username'] = $loginInfo['username'];
			$uid = M('Login')->where($where)->getField('id');
			session('uid',$uid);
			session('type',1);
			session('realname',$loginInfo['realname']);
			
			$expertInfo['uid'] = $uid;
			// 职称
			$expertInfo['level'] = getJobType(I('post.level'));
			// 学位
			$expertInfo['degree'] = I('post.degree');
			// 性别
			$expertInfo['gender'] = I('post.gender');
			// 出生日期
			$expertInfo['birthday'] = I('post.birthday');
			// 身份证号
			$expertInfo['identify'] = I('post.identify');
			// 政治面貌
			$expertInfo['political_status'] = I('post.political_status');
			// 复选框，以数组形式存储值
			// 研究领域
			$research = I("post.research");
			$expertInfo['research'] = getResearchType($research);
			// 研究内容
			$expertInfo['research_content'] = I('post.research_content');
			// 工作单位
			$expertInfo['work_college'] = I('post.college');
			// 办公电话
			$expertInfo['telphone'] = I('post.telphone');
			// 职务
			$expertInfo['jobname'] = I('post.jobname');
			// 个人简介
			$expertInfo['info'] = I('post.info');
			$result = M('ExpertUser')->data($expertInfo)->add();
			$this->success("欢迎你,".$loginInfo['realname'],U('Personal/index'));
		}else{
			$this->error("很可惜，注册失败了",U('Signup/signup'));
		}
	}

		/**
	 * 显示公司注册的页面
	 *
	 * @return [type] [description]
	 */
	public function signup_company(){
		$city = M("City")->select();
		$this->assign('city',$city);
		$this->display();
	}

	/**
	 * 处理企业注册的信息
	 *
	 * @return [type] [description]
	 */
	public function do_signup_company(){
		// type =2
		$loginInfo['username'] = I('post.username');
		// 密码加密
		$loginInfo['password'] = md5($_POST['pwd']);
		$loginInfo['type'] = 2;
		$loginInfo['realname'] = I('post.com_name');
		if(!empty($_FILES)){//有图上传
			// 返回值不为null -> 上传成功
			if(($logoPath = uploadPicture($_FILES,'user_logo'))){
				$loginInfo['logo'] = $logoPath;
			}
		}
		$loginInfo['mobile_phone'] = I('post.com_phone');
		$loginInfo['email'] = I('post.email');
		$result = M('Login')->data($loginInfo)->add();
		/*
			若信息在login表中添加不成功，则没有必要继续
		*/
		if($result){
			$where['username'] = $loginInfo['username'];
			$uid = M("Login")->where($where)->getField('id');
			session('uid',$uid);
			session('type',2);
			session('realname',$loginInfo['realname']);//企业名
			$companyInfo['uid'] = $uid;
			$companyInfo['website']=I('post.website');//企业官网
			$companyInfo['company_type'] = I('post.company_type');//企业标签
			$city['city']=$city['cityname'];
			$companyInfo['city'] =M('city') ->where($city)->getField('id');

			$companyInfo['area']=I('post.adress');//具体地址
			$research = I("post.research");
			$companyInfo['research'] = getResearchType($research);//经营方向
			$companyInfo['linkman']=I('post.linkman');//联系人
			$companyInfo['jobname'] = I('post.jobname');//联系人职务
			$companyInfo['link_phone'] = I('post.phone');//联系人电话
			$companyInfo['linkEmail']=I('post.linkEmail');//联系人邮箱
			$companyInfo['info']=I('post.info');//企业简介
			$result = M('CompanyUser')->add($companyInfo);
			$this->success("欢迎你,".$loginInfo['realname'],U('Personal/index'));
		}else{
			$this->error("注册失败，请稍后再试",U('Signup/signup'));
		}
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
		$loginInfo['username'] = I('post.username');
		// 密码加密
		$loginInfo['password'] = md5($_POST['pwd']);
		$loginInfo['type'] = 0;
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
		if($result){
			$where['username'] = $loginInfo['username'];
			$uid = M("Login")->where($where)->getField('id');
			session('uid',$uid);
			session('type',0);
			session('realname',$loginInfo['realname']);
			//用户名
			$this->success("欢迎你,".$loginInfo['realname'],U('Personal/index'));
		}
		else{
			$this->error("注册失败，请稍后再试",U('Signup/signup'));
		}
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
		$this->assign("username",I('get.un',null));
		$this->display();
	}

	/**
	 * 找回忘记密码第三步:显示页面
	 *
	 * @return [type] [description]
	 */
	public function forgetPwd_step3(){
		$this->assign("username",I('get.un',null));
		$this->display();
	}


	/**
	 * 找回忘记密码第一步:处理数据
	 *
	 * @return [type] [description]
	 */
	public function do_forgetPwd_step1(){
		$info['username'] = I('post.username');
		$info['realname'] = I('post.realname');
		$result = M('Login')->where($info)->find();
		// 找到用户
		if($result){
			$this->redirect('Signup/forgetPwd_step2?un='.$info['username']);
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
		if(I('post.username',null)){
			$where['username']=I('post.username');
			$result = M('Login')->where($where)->find();
			if($result){$where['mobile_phone'] = I('post.phone');
			$where['email'] = I('post.email');
				$this->redirect('Signup/forgetPwd_step3?un='.$where['username']);	
			}
			else{
				$this->error("输入信息不正确",U('Signup/forgetPwd_step2',array('un'=>$where['username'])));
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
				$result = M('Login')->where($where)->save($info); 
				if($result){
					$this->success("修改成功，请登录",U('Public/login'));
				}
				else{
					$this->error('修改失败，请重试',U('Signup/forgetPwd_step3',array('un'=>$where['username'])));
				}

			}
			else{
				$this->error('两次密码不相同',U('Signup/forgetPwd_step3',array('un'=>$where['username'])));
			}
			
		}
	}
}

?>