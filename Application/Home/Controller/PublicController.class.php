<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 公用内容
 */
class PublicController extends Controller {
	/**
	 * 展示登陆页面
	 *
	 */
	public function login()	{
		$this->display();
	}

	/**
	 * 处理登陆逻辑
	 *
	 */
	public function dologin(){
		$name['username'] = I('post.username',false);
		$pwd = $_POST['password'];
		if(!$name['username']){
			$this->error("用户名为空",U('Public/login'));
		}
		$res = M('Login')->where($name)->find();
		if($res){
			if($res['password'] == md5($pwd)){
				session('uid',$res['id']);
				session('type',$res['type']);
				session('realname',$res['realname']);
				$this->success("欢迎你：".$realname,U('Personal/index'));
			}else{
				$this->error("密码错误",U('Public/login'));
			}
		}else{
			$this->error("用户名不存在",U('Public/login'));
		}
	}

	/**
	 * [注销]
	 *
	 * @return [type] [description]
	 */
	public function logout(){
		session(null);
		$this->redirect("Home/Index/index");
	}
	
	public function link(){
		/*当前将连接类型分为三类：
			连接专家     => 0，
			连接团队，	 => 1,
			连接技术需求 => 2
		*/ 
		switch (I('get.linkType')) {
			case 0:
				$linkType = "对接专家";
				break;
			case 1:
				$linkType = "对接专家团队";
				break;
			default:
				$linkType = "对接技术需求";
				break;
		}
	}
    public function aboutus(){
      $this->display();
    }
}
?>