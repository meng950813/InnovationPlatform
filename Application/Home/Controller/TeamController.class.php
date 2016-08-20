<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 专家团队
 */
class TeamController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function teamlist(){ 
        $where['status'] = 1;
        if(I('get.type')){
            $type = getResearchType(I('get.type'));
            $where['team_research'] = array("like","%".$type."%");
        }
        $ExpertTeam = M('ExpertTeam');
        $list =$ExpertTeam->where($where)->order('sort desc')->page(I('get.p',1).',20')->getField('teamid,teamname,workplace,team_research,research_content,setup_time');
        
        // 分页 
        $count =$ExpertTeam->where($where)->count();
        $page = new \Think\Page($count,20);
        $show = $page->show();
        
        $this->assign("list",$list);
        $this->assign("page",$show);
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function team(){
        $where['teamid'] = I('get.id');

        // 获取团队信息
        $teamInfo = D("TeamInfoView")->where($where)->find();

        // 获取研究成果信息
        $research['owner_id'] = $where['teamid'];
        $research['isteam'] = 1;
        $research_result = M("ResearchResult")->where($research)->select();

        // 获取团队荣誉
        $award['award_owner'] = $where['teamid'];
        $award['isteam'] = 1;
        $awardInfo = M("Award")->where($award)->select();

        //  团队总人数
        $teamInfo['member_num'] = M('TeamMember')->where($where)->count();

         // 方便前端判断字段，0表示信息不可见,无超链接
        $agree = 0;
        // 判断用户是否登录
        if(session('uid')){
            // 已登录，判断是否是团队成员
            // 标志是否能够看到信息的变量，true表示不能看到
            $isNone = true;
            if(session('uid') == $teamInfo['teamleader']){
                // 是团队领导人
                $isNone = false;
            }
            else{
                $arr['uid'] = session('uid');
                $arr['teamid'] = $where['teamid'];
                $isMember = M('TeamMember')->where($arr)->find();
                if($isMember){
                    // 是团队成员，可以看到信息
                    $isNone = false;
                }
            }
            // 如果用户是团队成员 或通过联系 都能看到联系信息
            if($isNone || isLinked(session('uid'),$where['teamid'],1)){
                // 通过联系，获取 团队领导人+联系人 信息
                $leaderid['uid'] = $teamInfo['teamleader'];
                // 信息包括：姓名，手机，办公电话，email，工作单位，职务
                // $leader = D("ExpertInfoView")->where($leaderid)->getField('uid,realname,mobile_phone,telphone,email,work_college,jobname');
                $leader = D("ExpertInfoView")->where($leaderid)->find();

                // 方便前端判断 字段，表示信息可见,可以使用超链接
                $agree = 1;

                // 获取成员信息，包括：id,姓名，职称,研究领域
                $Member_info = M("TeamMember")->where($where)->select();
                $ExpertUser = M('ExpertUser');
                $size = count($Member_info);
                for ($i=0; $i < $size; $i++) {
                    $expert_info = $ExpertUser->where('uid=%d',$Member_info[$i]['uid'])->find();
                    $Member_info[$i]['level'] = $expert_info['level'];
                    $Member_info[$i]['research'] = $expert_info['research'];
                }
            }
            else{
                // 未通过联系，联系信息置为 *
                $leader = team_none($leader);
                for ($i=0; $i < $teamInfo['member_num']; $i++) { 
                    $Member_info[$i]['member_name'] = "********";
                    $Member_info[$i]['level']       = "********";
                    $Member_info[$i]['research']    = "********";
                }
            }
        }
        else{
            // 未登录,联系信息不可见
            $leader = team_none($leader);
            for ($i=0; $i < $teamInfo['member_num']; $i++) { 
                $Member_info[$i]['member_name'] = "********";
                $Member_info[$i]['level']       = "********";
                $Member_info[$i]['research']    = "********";
            }
        }
// var_dump($leader);
        $this->assign("teamInfo",$teamInfo);
        $this->assign("research",$research_result);
        $this->assign("award",$awardInfo);
        $this->assign("leader",$leader);
        $this->assign("memberInfo",$Member_info);
        $this->assign("agree",$agree);
    	$this->display();
    }

    /**
     * 显示创建团队页面
     *
     * @return [type] [description]
     */
    public function add(){
        $this->display();
    }

    /**
     * 处理创建团队信息
     *
     * @return [type] [description]
     */
    public function addTeam(){
        islogin();

        $info['teamname'] = I('post.teamname');
        $info['team_research'] = getResearchType(I('post.research_type'));
        // echo $info['team_research'];
        $info['teamleader'] = session('uid');
        $info['research_content'] = I('post.research_content');
        $info['workplace'] = I('post.workplace');
        $info['info'] = I('post.info');
          // 有图片上传
        if(!empty($_FILES)){
            // 上传成功，返回路径，否则返回null
            if(($res = uploadPicture($_FILES,'team_logo'))){
                $info['team_logo'] = $res;
            }
        }
        // 创建团队
        $result = M("ExpertTeam")->data($info)->add();
        if($result){
            // 获取团队信息，将用户添加到团队成员中
            $where['teamname'] = $info['teamname'];
            $member['teamid'] = M('ExpertTeam')->where($where)->getField('teamid');
            $member['uid'] = session('uid');
            $member['member_name'] = session('realname');

            $addMember = M('TeamMember')->data($member)->add();
            if($addMember){
                // 正常流程，团队创建成功，创建人加入团队成员 表
                $this->success("团队创建成功",U('Team/teamlist'));
            }
            else{
                // 意外流程，团队创建成功，创建人没有加入团队成员表
                // 解决措施 --> 重新创建团队
               $this->error("创建失败，请重试",U('Team/add'));
            }
        }else{
            $this->error("创建失败，请重试",U('Team/add'));
        }
    }

}
?>