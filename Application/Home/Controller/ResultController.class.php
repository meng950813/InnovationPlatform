<?php
namespace Home\Controller;
use Think\Controller;

/**
* create by cm
 * 科研成果
 */
class ResultController extends Controller {

/**
     * 科技成果列表页
     *
     * @return [type] [description]
     */
    public function resultlist(){
        $where['status']=1;
        if(!empty($_GET['type'])){
            $type = getResearchType($_GET['type']);
            $where['research_type'] = array("like","%".$type."%");
        }
        $list = M('ResearchResult')->where($where)->order('sort desc')->page(I('get.p',1).',20')->select();
      
        // 分页
        $count = M('ResearchResult')->where($where)->count();
        $page = new \Think\Page($count,20);
        $show = $page->show();
      
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    /**
     * 详情页
     *
     * @return [type] [description]
     */
    public function result(){
        // 获取成果信息
        $where['result_id'] = I('get.id');
        // 从表里拿数据是这一条语句
        $resultInfo = M("ResearchResult")->where($where)->find();//找出这条对应信息

        $resultInfo['total_info'] = replace_br($resultInfo['total_info']);
        $resultInfo['tech_target'] = replace_br($resultInfo['tech_target']);

        // 获取这条成果带来的成就信息
        $award['research_id'] = $resultInfo['result_id'];
        $awardInfo = M('Award')->where($award)->select();

        // 用户已登录
        if(session('uid')){
            if($resultInfo['isteam'] == 0){
                // 表示是个人成果 
                $owner['id'] = $resultInfo['owner_id'];
                // 获取用户名，手机，邮箱
                $info = M('Login')->where($owner)->find();

                // 不是发布者本身查看 同时与发布者没有联系
                if ((session('uid') != $owner['id']) && (!isLinked(session('uid'),$owner['id'],0))) {
                    // 将联系信息置为 ******
                    $info['realname']           = "********";
                    $info['mobile_phone']       = "********";
                    $info['email']              = "********";
                    $ownerInfo['telphone']      = "********";
                    $ownerInfo['jobname']       = "********";
                    $ownerInfo['level']         = "********";
                    $ownerInfo['work_college']  = "********";
                    $ownerInfo['linkman']       = "********";
                    $ownerInfo['linkEmail']     = "********";
                    $ownerInfo['link_phone']    = "********";

                }
                else{
                    // 当前用户有权限看到信息
                    // 从数据库获取发布者数据
                    $where['uid']=$resultInfo['owner_id'];
                    if($info['type'] == 1){
                        // 专家和企业表里是 uid
                        $ownerInfo=M('ExpertUser')->where($where)->find();
                    }
                    else if($info['type'] == 2){
                        // 企业
                        $ownerInfo=M('CompanyUser')->where($where)->find();
                    }
                }
                $this->assign('title',$info);
            }
            else{
                // 团队发布成果
                // 获取团队成员信息
                $info['teamid']=$resultInfo['owner_id'];
                $info['uid'] = session('uid');
                // 获取团队成员信息，包括：uid，realname(真实姓名),level(职称),research(研究领域)
                $member=D('MemberInfoView')->where($info)->select();
                //获取团队信息，包括团队名，和领导人
                $team['teamid'] = $resultInfo['owner_id'];
                $teamInfo = M('ExpertTeam')->where($team)->field('teamname,teamleader')->find();
                $TeamMember = M('TeamMember');
                // 如果在团队表中可以找到信息 ，表示当前用户是团队一员 
                // 或当前用户不是团队一员，但与团队有联系
                $result = $TeamMember->where($info)->find();
                if($result || isLinked($info['uid'],$resultInfo['owner_id'],1)){
                    // 获取联系人(teamleader)信息
                    $tleader['uid'] = $teamInfo['teamleader'];
                    $ownerInfo = D('ExpertInfoView')->where($tleader)->find();
                }
                // 当前用户没有权限看联系信息
                else{
                    $size = count($member);
                    for ($i=0; $i < $size; $i++) { 
                        $member[$i]['member_name']  ="********";
                        $member[$i]['level']        ="********";
                        $member[$i]['research']     ="********";
                    }
                    $ownerInfo['realname']     = "********";
                    $ownerInfo['telphone']     = "********";
                    $ownerInfo['email']        = "********";
                    $ownerInfo['mobile_phone'] = "********";
                    $ownerInfo['work_college'] = "********";
                    $ownerInfo['jobname']      = "********";
                }
                $this->assign("title",$teamInfo); 
            }
               
        }
        // 未登录的游客
        else{
            if($resultInfo['isteam'] == 1){
                $arr['teamid'] = $resultInfo['owner_id'];
                $title = M('ExpertTeam')->where($arr)->field('teamid,teamname')->find();
            }
            else{
                $arr['id'] = $resultInfo['owner_id'];
                $title = M('Login')->where($arr)->field('id,realname')->find();
            }
            $member['member_name']     = "********";
            $member['level']           = "********";
            $member['research']        = "********";
            $ownerInfo['realname']     = "********";
            $ownerInfo['telphone']     = "********";
            $ownerInfo['email']        = "********";
            $ownerInfo['mobile_phone'] = "********";
            $ownerInfo['work_college'] = "********";
            $ownerInfo['jobname']      = "********";
            $this->assign('title',$title);
        }   
        $this->assign("agree",$agree);
        $this->assign("memberInfo",$member);
        $this->assign('ownerInfo',$ownerInfo);
        $this->assign("resultInfo",$resultInfo);
        $this->assign("awardInfo",$awardInfo);
        $this->display();
    }

    /**
     * 科技成果添加页
     *
     * @return [type] [description]
     */
    public function add(){
        $this->display();
    }

    /**
     * 添加处理函数
     *
     * @return [type] [description]
     */
    public function addResearch(){
        islogin();

        $info['result_name'] = I('post.research_title');
        if(I('post.research_type') == ''){
            $this->error("请选择成果类型");
        }
        $info['research_type'] = getResearchType(I('post.research_type'));

        if(I('post.isteam') == 1){
            $where['teamname'] = I('post.team_name');
            $info['owner_id'] = M('ExpertTeam')->where($where)->field('teamid')->getField();
            $info['isteam'] = 1;
        }
        else{
           $info['owner_id'] = session('uid'); 
           $info['isteam'] = 0;
        }

        $info['end_time'] = I('post.end_time');

        $info['research_level'] = I('post.research_level');

        // 获取当前日期
        $info['publish_time'] = date('Y/m/d');

        $info['total_info'] = I('post.total_info');
        
        $info['tech_target'] = I('post.tech_target');
        if (!empty($_FILES)) {//有图片上传
            // 返回值不为null -> 上传成功
            if(($research_picture = uploadPicture($_FILES,'research_picture'))){
                $info['result_picture'] = $research_picture;
            }
        }

        $ResearchResult = M('ResearchResult');
        $result =$ResearchResult->add($info);

        if($result){
            $uploaderInfo = $ResearchResult ->where(array("result_name"=>$info['result_name'],"owner_id"=>$info['owner_id']))->order("sort desc")->field("result_id")->find();

            // 获取填写的专利内容
            $award = I("post.award");

            // 如果填写内容不为空
            if(!empty($award)){
                $awardSzie = count($award);
                for ($i=0; $i < $awardSzie; $i++) { 
                    // 表示已经到最后一条
                    if($award[$i] == ""){
                        break;
                    }

                    $awardInfo[]=array(
                            "award_owner" => $info['owner_id'],
                            "award_title" => $award[$i],
                            "isteam"      => $info['isteam'],
                            "research_id" => $uploaderInfo['result_id'],
                            "get_time"    => $info['publish_time'],
                            "sort"        => date("Y-m-d H:i:s",time())
                        );

                }

                M("Award")->addAll($awardInfo);
            }
           
            $this->success("发布成功",U('Result/resultlist'));
        }
        else{
            $this->error("发布失败，请稍后重试",U('Result/resultlist'));
        }
    }
}
?>