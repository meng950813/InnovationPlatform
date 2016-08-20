<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 个人中心
 */
class PersonalController extends Controller {

	/**
    * 个人中心首页
    * 显示所有联系信息
    */
    public function index(){
    	islogin();
    	$where['request_id'] = session('uid');
    	$where['status'] = 1;
    	$linkInfo = D('RequireInfoView')->where($where)->order('sort desc')->field('link_id,request_id,link_type,teamid,realname,type')->select();
    
    	if($linkInfo){
    		$this->assign("list",$linkInfo);
    	}
    	else{
    		$no_link = "暂无";
    		$this->assign("no_link",$no_link);
    	}
    	$this->display();
    }

    /**
    *显示联系信息详情
    *
    */
    public function link_info(){
     	$where['link_id'] = I('get.id');
     	$info = D('RequireInfoView')->where($where)->find();
     	if($info['teamid']!=null){
     		$arr['teamid'] = $info['teamid'];
     		$info['teamname'] = M('ExpertTeam')->where($where)->getField('teamname');
     	}
     	$this->assign("linkInfo",$info);

    	$this->display();
    }

    /**
    * 处理联系信息
    */
    public function deal_link(){
     	islogin();

    	$agree = I('get.re');
    	$where['id'] = I('get.id');
    	// 同意联系
    	if($agree){
    		// 获取联系的具体信息
    		$result = M('RequireLink')->where($where)->field('id,require_id,request_id,link_type,teamid')->find();
    		// 本条消息：团队邀请队员
    		if($result['link_type'] == 2 && $result['teamid'] != null){
    			$team['teamname'] = $result['teamid'];
    			$team['uid'] = $result['require_id'];
    			$team['realname'] = M('Login')->where(array('id'=>$result['require_id']))->getField('realname');
    			// 将数据添加到team_member 表
    			$addMember = M('TeamMember')->add($team);
    			if($addMember){
    				// 成功加入团队
    				// 将邀请信息中的 status 置为 0，表示已处理
    				setStatus($result['id']);
    				$this->success("成功加入团队",U('Team/team?id='.$result['teamid']));
    			}
    			else{
    				// 加入团队不成功
    				$this->error("服务器忙，请稍候",U('Personal/index'));
    			}	
    		}
    		// 本条消息：个人联系个人/团队
    		else{
    			$link['require_id'] = $result['require_id'];
    			$link['request_id'] = $result['request_id'];

                $exsit = M('Linked')->where($link)->find();
                if($exsit){
                    // 连接已存在
                    setStatus($result['id']);
                    $this->success("处理成功",U('Personal/index'));
                }
                // 连接不存在，添加
                else{
                    // 本条消息  联系团队
                    if($result['link_type'] == 1 && $result['teamid'] != null){
                        $link['link_type'] = 1;
                        $link['teamid'] = $result['teamid'];
                    }
                    // 若是 个人联系个人，后两个字段取默认值
                    // 在 Linked 表中添加数据
                    $addLink = M("Linked")->add($link);
                    if ($addLink) {
                        // 添加成功,将联系消息置为0，已处理
                        setStatus($result['id']);
                    }
                    else{
                        // 添加数据不成功
                        $this->error("服务器忙，请稍候",U('Personal/index'));
                    }
                }
    			
    		}
    	}
    	// 拒绝联系/邀请
    	else{
    		// 将消息列表中的status置为0，已处理
    		if(setStatus(I('get.id'))){
    			$this->success("成功处理",U("Personal/index"));
    		}
    		else{
    			$this->error("服务器忙，请稍候",U('Personal/index'));
    		}
    	}
    }

    /**
    * 显示 与我有关的团队信息
    */
    public function my_team(){

        // 获取所有与我有关的团队信息
        $where['uid'] = session('uid');
        $allTeam = D('UserTeamView')->where($where)->order('sort desc')->select();
        // 遍历所有团队，若teamleader 与我相同，则是我创建的团队，否则是我加入的团队
        // 先声明两个变量分别用来存储 两个信息
        $myTeam;
        $joinTeam;
        $teamNum = count($allTeam);
        for ($i=0; $i < $teamNum; $i++) {
            echo $allTeam['teamleader'];
            if($allTeam[$i]['teamleader'] == $where['uid']){
                // 我创建的团队  
                $myTeam[$i] = $allTeam[$i];
            }
            else{
                // 我加入的团队
                $joinTeam[$i] = $allTeam[$i];
            }
        }

        $this->assign("teamlist",$myTeam);
        $this->assign("joinlist",$joinTeam);
        $this->display();
    }

    /**
     * 显示修改团队信息 页面
     */
    public function modify_team(){
        $where['teamid'] = I('get.id');
        $teamInfo = M('ExpertTeam')->where($where)->find();
        $teamInfo['info'] = replace_br($teamInfo['info']);
        $this->assign("info",$teamInfo);
        $this->display();
    }

    /**
     * 修改我创建的团队 信息
     */
    public function modify_my_team(){
        $where['teamid'] = I('post.teamid');
        $info['teamname'] =I('post.teamname');
        if(!empty($_FILES)){
            $info['team_logo'] = uploadPicture($_FILES,'team_logo');
        }
        if(I('post.research_type') != ''){
            $info['team_research'] = getResearchType(I('post.research_type'));
        }
        $info['research_content'] = I('post.research_content');
        $info['workplace'] = I('post.workplace');
        $info['teamInfo'] = I('post.info');

        $result = M("ExpertTeam")->where($where)->save($info);
        if($result){
            $this->success("修改成功！");
        }
        else{
            $this->error("修改失败，请稍后重试",U('Personal/my_team'));
        }
    }

    /**
    * 显示 与我有关的研究成果
    */
    public function my_research(){ 

        $ResearchResult = M("ResearchResult");

        // 我的成果 -> 个人研究成果
        $where['owner_id'] = session('uid');
        $where['isteam'] = 0;
        $my_research =$ResearchResult->where($where)->order('sort desc')->getField('result_id,result_name');

        // 所在团队的研究成果
        $team['uid'] = session('uid');
        $myTeam = M('TeamMember')->where($team)->select();

        $teamNum = count($myTeam);
        for ($i=0; $i < $teamNum; $i++) {
            $array[$i] = $myTeam[$i]['teamid'];
        }
        $research['owner_id'] = array('in',$array);
        $research['isteam'] = 1;
        $team_research = $ResearchResult->where($research)->order('sort desc')->field('result_name,result_id,owner_id')->select();
 
        // 判断当前用户是否是团队负责人，以确定是否要给用户修改这个团队成果的权利
        $Team = M('ExpertTeam');
        $cout = count($team_research);
        for ($i=0; $i < $cout; $i++) {
            $tid['teamid'] = $team_research[$i]['owner_id'];
            $leader = $Team->where($tid)->getField('teamleader');
            // 如果是
            if(session('uid') == $leader){
                $team_research[$i]['leader'] = 1;
            }
            else{
                $team_research[$i]['leader'] = 0;
            }
        }

        $this->assign("my_research",$my_research);
        $this->assign("team_research",$team_research);
        $this->display();
    }

    /**
    * 显示 与我有关的成就/荣誉
    */
   public function my_award(){

        $Award = M('Award');
        // 我的荣誉
        $myInfo['award_owner'] = session('uid');
        $myInfo['isteam'] = 0;
        $myAward = $Award->where($myInfo)->order('sort desc')->select();

        // 团队荣誉
        $team['uid'] = session('uid');
        // 获取我的团队信息
        $myTeam = M('TeamMember')->where($team)->select();

       $count = count($myTeam);
       for ($i=0; $i < $count; $i++) { 
           $array[$i] = $myTeam[$i]['teamid'];
       }
       // 获取所有我参与的团队的成就
       $where['award_owner'] = array('in',$array);
       $where['isteam'] = 1;
       $teamAward = $Award->where($where)->order('sort desc')->field('id,research_id,award_title')->select();
       
        // 判断当前用户是否是团队负责人，以确定是否要给用户修改这个团队成果的权利
        $Team = M('ExpertTeam');
        $size = count($teamAward);
        for ($i=0; $i < $size; $i++) {
            $team['teamid'] = $teamAward[$i]['award_owner'];
            $leader = $Team->where($team)->getField('teamleader');
            // 判读团队的领导人是不是当前用户
            if($leader == session('uid')){
                $teamAward[$i]['leader'] = 1;
            }
            else{
                $teamAward[$i]['leader'] = 0;
            }
        }

        $this->assign("awardlist",$my_award);
        $this->assign("teamaward",$teamAward);
        $this->display();
   }

   /**
    * 显示 个人信息
    *
    * @return [type] [description]
    */
    public function my_info(){

        // 获取基本信息
        $where['id'] = session('uid'); 
        $basicInfo = M('Login')->where($where)->find();
    
        $this->assign("basicInfo",$basicInfo);
        $this->display();
    }

    /**
     * 显示用户详细信息
     *
     * @return [type] [description]
     */
    public function detail_info(){
        // 获取详细信息
        $arr['uid'] = session('uid');
        if(session('type') == 1){
            // 专家用户
            $myinfo = M('ExpertUser')->where($arr)->find();
        }
        else if(session('type') == 2){
            // 企业用户
            $myinfo = M('CompanyUser')->where($arr)->find();
            $Citylist=  M('City');
            $where['id'] = $myinfo['city'];
            $myinfo['cityname'] = $Citylist->where($where)->getField('cityname'); 
            $city =$Citylist->select();
            $this->assign("city",$city);
        }

        $this->assign("myinfo",$myinfo);
        $this->display();
    }

    /**
     * 重新上传用户头像
     *
     * @return [type] [description]
     */
    public function upload_logo(){
        if(!empty($_FILES)){
            //有图片上传
            $where['id'] = session('uid');
            $logo['logo'] = uploadPicture($_FILES,'user_logo');
            $result = M('Login')->where($where)->save($logo);
            if($result){
                $this->success("修改成功");
            }
            else{
                $this->error("修改失败，请稍后重试");
            }
        }
        else{
            $this->error("没有上传图片");
        }       
    }

    /**
     * 修改用户基础信息
     *
     * @return [type] [description]
     */
    public function modify_basicinfo(){
        $info['id'] = session('uid');    
        $info['realname'] = I('post.realname');
        $info['mobile_phone'] = I('post.mobile_phone');
        $info['email'] = I('email');
        $result = M('Login')->save($info);
        if($result){
            $this->success("修改成功");
        }else{
            $this->error('修改失败');
        }
    }

    /**
     * 修改密码
     *
     * @return [type] [description]
     */
    public function modify_pwd(){
        $where['uid']=session('uid');
        $Login=M('Login');
        $pwd=$Login->where($where)->find();
        if(I('post.pwd1')==""){
            $this->error("密码不能为空");
        }
        if($pwd['password']!=md5(I('post.pwd1'))){
            $this->error("密码错误");
        }
        if(I('post.pwd2')!=I('post.pwd3')){
            $this->error("两次密码不同！");
        }
        $code = I('post.verfigcode',false,'string');
        if(!check_verify($code)){
            $this->error('验证码错误');
        }
        $res = $Login->where($pwd)->setField('password', md5(I('post.pwd2')));
        if($res){
            $this->success("修改成功！");
        }else{
            $this->error("修改失败！");
        }
    }

    /**
     * 修改专家用户详细信息
     *
     * @return [type] [description]
     */
    public function modify_expert(){
        $where['uid'] = session('uid');
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
        // 判断用户是否 点击多选按钮，修改研究领域
        if(I('post.research')!= ''){
            $expertInfo['research'] = getResearchType(I('post.research'));
        }
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
        $result = M('ExpertUser')->where($where)->save($expertInfo);
        if($result){
            $this->success("修改成功");
        }
        else{
            $this->success("修改失败，请稍后重试",U('Personal/detail_info'));
        }
    }

    /**
     * 修改企业用户详细信息
     *
     * @return [type] [description]
     */
    public function modify_company(){
        $where['uid'] = session('uid');
        $info['website'] = I('post.website');
        $info['company_type'] = I('post.company_type');
        $info['city'] = I('post.city');
        $info['area'] = I('post.address');
        // 判断用户是否 点击多选按钮，修改研究领域
        if(I('post.research') != ''){
            $info['research'] = getResearchType(I('post.research'));
        }
        $info['linkman'] = I('post.linkman');
        $info['jobname'] = I('post.jobname');
        $info['link_phone'] = I('post.link_phone');
        $info['linkEmail'] = I('post.linkEmail');
        $info['info'] = I('post.info');
        $result = M('CompanyUser')->where($where)->save($info);
        if($result){
            $this->success("修改成功！");
        }
        else{
            $this->error("修改失败，请稍后重试",U('Personal/detail_info'));
        }
    }


    /**
    * 添加某项研究成果带来的 成就/论文
    */
    public function addAward(){
        // 获取要添加 成就的研究成果信息
        $research['result_id'] = I('get.id');
        $info = M('ResearchResult')->where($where)->field('result_id,result_name,isteam')->find();
        $this->assign('research',$info);
        $this->display();
    }

     /**
    * 处理添加数据
    */
    public function do_addAward(){
        $info['isteam'] = I('post.isteam');
        $info['research_id'] = I('post.id');
        $info['award_title'] = I('post.award_name');
        $info['get_time'] = I('post.get_time');
        $result = M('Award')->data($info)->add();
        if($result){
            $this->success("发布成功",U('Personal/my_award'));
        }
        else{
            $this->error("发布失败，请稍后重试");
        }
    }

    /**
     * 显示修改成就的页面
     *
     * @return [type] [description]
     */
    public function modify_award(){
        $where['id'] = I('get.id');
        $info = M("Award")->where($where)->find();
        $this->assign('info',$info);
        $this->display();
    }

    public function do_modify_award(){
        $info['id'] = I('post.id');
        $info['award_title'] = I('post.award_name');
        $info['get_time'] = I('post.get_time');
        $result = M("Award")->data($info)->save();
        if($result){
            $this->success("修改成功");
        }
        else{
            $this->error("修改失败，请稍后重试",U('Personal/my_award'));
        }
    }
    /**
    * 解散我创建的团队
    */
    public function del_team(){
        $where['teamid'] = I('get.id');
        $delTeam = M("ExpertTeam")->where($where)->delete();
        // 此处在数据库的表中设置删除级联，当团队被删除之后，team_member表中与该团队相关的数据自动清除
        if($delTeam){
            $this->success("团队解散成功");
        }
        else{
            $this->error("解散失败，请稍后重试");
        }
    }

     /**
    * 从某个团队离开
    */
    public function leave_team(){
        $where['teamid'] = I('get.id');
        $where['uid'] = session('uid');
        $result = M('TeamMember')->where($where)->delete();
        if($result){
            $this->success("成功脱离团队");
        }
        else{
            $this->error("脱离失败，请稍后重试");
        }
    }

    /**
     * 显示修改成果界面
     *
     * @return [type] [description]
     */
    public function modify_research(){
        $where['result_id'] = I('get.id');
        $info = M('ResearchResult')->where($where)->find();
        $this->assign("info",$info);
        $this->display();
    }

    /**
     * 处理修改逻辑
     *
     * @return [type] [description]
     */
    public function do_modify_research(){
        $info['result_id'] = I('post.id');
        $info['result_name'] = I('post.research_title');

        if(I('post.research_type') != ''){
            $info['research_type'] = getResearchType(I('post.research_type'));
        }
        $info['end_time'] = I('post.end_time');
        $info['research_level'] = I('post.research_level');
        $info['total_info'] = I('post.total_info');
        $info['tech_target'] = I('post.tech_target');
        if (!empty($_FILES)) {//有图片上传
            // 返回值不为null -> 上传成功
            if(($result = uploadPicture($_FILES,'research_picture'))){
                $info['result_picture'] = $result;
            }
        }
        $result = M('ResearchResult')->data($info)->save();
        if($result){
            $this->success("修改成功");
        }
        else{
            $this->error("修改失败，请稍后重试",U('Personal/my_research'));
        }
    }
    /**
    * 删除某条成果
    */
    public function del_research(){
        $where['result_id'] = I('get.id');
        $result = M('ResearchResult')->where($where)->delete();
        if($result){
            $this->success("删除成功");
        }
        else{
            $this->error("删除失败，请稍后重试");
        }
    }


}
?>