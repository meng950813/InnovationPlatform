<?php
namespace Home\Controller;
use Think\Controller;
/**
* create by cm
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
				$this->success("欢迎你：".$res['realname'],U('Personal/index'));
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

	/**
	* 判断输入的姓名是否已被注册
	*/
	public function checkName(){
		// 如果获取的参数是username
		// 表示 在注册页面，申请新的用户名
		if(I('post.username',null)){
			// 获取用户名
			$where['username'] = I('post.username');
			// 在注册表中查找姓名
			$result = M('Login')->where($where)->find();
		}
		// 获取到团队名和leader
		// 表示 在邀请加入团队页面，判断团队名与leader是否匹配
		else if(I('post.teamname',null)&&I('post.leader',null)){
			$where['teamname'] = I('post.teamname');
			$where['teamleader'] = I('post.teamleader');
			// 若找到，证明输入信息正确
			$result = M('ExpertTeam')->where($where)->find();
		}
		// 获取到的参数是 teamname
		// 表示在注册页面中，申请新的团队名
		else if(I('post.teamname',null)){
			// 获取团队名
			$where['teamname'] = I('post.teamname');
			$result = M('ExpertTeam')->where($where)->find();
		}
		// 查找有结果 -> 该用户名已被注册/teamleader输入正确的团队名
 		if($result){
 			$data['code'] = 1;
			$this->ajaxReturn($data);
			// $this->ajaxReturn(['code'=>1]);
		}else{
			$data['code'] = 0;
			$this->ajaxReturn($data);
		}
	}
	
	public function link(){
		/*当前将连接类型分为四类,用于页面显示：
			连接专家     	=> 1，
			连接团队	 	=> 2,
			连接企业	 	=> 3，
			连接成果	 	=>4、


		数据库中连接类型只分三类：
			个人 连接 个人  =>0  默认
			个人 连接 团队  =>1
			团队 邀请 个人  =>2
		*/ 
			
		switch (I('get.linkType')) {
			case 1:
				$linkTip = "对接专家";
				$linkInfo['owner'] = I('get.id');
				break;
			case 2:
				$linkTip = "对接专家团队";
				//  联系 团队创始人
				$where['teamid'] = I('get.id');
				$linkInfo['owner'] = M("ExpertTeam")->where($where)->getField('teamleader');
				break;
			case 3:
				$linkTip = "对接技术需求";//企业
				$where['demand_id'] = I('get.id');
				$linkInfo['owner'] = M('Demand')->where($where)->getField('demand_com');
				break;
			default:
				$linkTip = "对接科技成果";
				$where['result_id'] = I('get.id');
				// 通过成果 id 判断是否是团队成果
				$researchInfo = M('ResearchResult')->where($where)->find();
				//团队成果 => 联系团队创始人
				if($researchInfo['isteam'] == 1){
					$arr['teamid'] = $researchInfo['owner_id'];
					$temp = M("ExpertTeam")->where($arr)->field('teamleader')->find();
					$linkInfo['owner'] = $temp['teamleader'];
				}
				// 个人成果 = > 联系个人
				else{
					$linkInfo['owner'] = $researchInfo['owner_id'];
				}
				break;
		}
		$linkInfo['linkType'] = I('get.linkType');
		$linkInfo['linkTip'] = $linkTip;
		$linkInfo['id'] = I('get.id');
		$this->assign("linkInfo",$linkInfo);
		$this->display();
	}

	public function dolink(){
		islogin();

		$info['require_id'] = session('uid');

		$info['request_id'] = I('post.owner');
		if(I('post.type') == 2){
			// 个人联系团队
			$info['type'] = 1;
		}
		else{
			// 个人联系个人
			$info['type'] = 0;
		}
		$info['title'] = I('post.title');
		$info['content'] = I('post.content');
		$info['remark'] = I('post.remark');
		$result = M('RequireLink')->data($info)->add();
		switch (I('post.type')) {
			case 1:
				$path = 'Expert/expertlist';
				break;
			case 2:
				$path = 'Team/teamlist';
				break;
			case 3:
				$path = 'Demand/demandlist';
				break;
			default:
				$path = 'Result/resultlist';
				break;
			}
		if($result){
			$this->success("请求发布成功!",U($path));
		}else{
			$this->error("请求发布失败，请稍候重试",U($path));
		}
	}

	public function verfig(){
		$config = array(
			'imageW' => '0',
			'imageH' => '34',
			'fontSize' => 16,		// 验证码字体大小
			'length' => 4,			// 验证码位数
			'useNoise' => false,	// 关闭验证码杂点
			'useCurve' => false,
			);
		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}

	public function aboutus(){
        $this->display();
    }

    public function loading(){
        $this->display();
    }
}
?>