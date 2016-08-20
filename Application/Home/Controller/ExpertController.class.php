<?php
namespace Home\Controller;
use Think\Controller;

/**
* create by cm
* 专家信息
*/
class ExpertController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function expertlist(){
        $where;
        if(I('get.type')){
            $type = getResearchType(I('get.type'));
            $where['research'] = array("like","%".$type."%");
        }
        $ExpertInfo = D("ExpertInfoView");
        $expertlist = $ExpertInfo->where($where)->order('sort desc')->page(I('get.p',1).',20')->getField('id,realname,work_college,logo,level,research,research_content,setup_time');
        
        // 分页，一页20条数据
        $count = $ExpertInfo->where($where)->count();
        $page = new \Think\Page($count,20);
        $show = $page->show();

        $this->assign("list",$expertlist);
        $this->assign("page",$show);
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function expert(){
        $where['uid'] = I('get.id');
        $ExpertInfo = D('ExpertInfoView');
        // 获取专家信息
        $expertInfo = $ExpertInfo->where($where)->find();
        // 将 后端 换行符转换为 前端换行符
        $expertInfo['info'] = replace_br($expertInfo['info']);
        

        // 获取这位专家的研究成果信息
        $research['owner_id'] = $where['uid'];
        // 非团队成果
        $research['isteam'] = 0;
        $research['status'] = 1; 
        $researchInfo = M('ResearchResult')->where($researc)->order('sort desc')->getField('result_id,result_name,research_type,research_level');

        // 获取这位专家的获奖情况
        $award['award_owner'] = $where['uid'];
        $award['isteam'] = 0;
        $awardInfo = M('Award')->where($award)->order('sort desc')->getField('research_id,award_title,get_time');

        // 如果当前用户与观看的用户没有联系 且 不是用户本身，将信息遮盖
        if((!isLinked(session('uid'),$where['uid'],0))&&(session('uid') != $where['uid'])){
            // 表示未登录或与之没有有联系                   不是用户本身的信息
            $expertInfo['identify']     = "********";
            $expertInfo['mobile_phone'] = "********";
            $expertInfo['email']        = "********";
            $expertInfo['telphone']     = "********";
        }
            
        $this->assign('expertInfo',$expertInfo);
        $this->assign('research',$researchInfo);
        $this->assign('award',$awardInfo);
    	$this->display();
    }


      /**
    * 显示邀请队员界面
    */
    public function joinTeam(){
        // 获取 被邀请人的id
        $this->assign('id',I('get.id'));
        $this->display();
    }

    /**
    * 处理邀请信息
    */
    public function join(){
        islogin();
        
        $info['require_id'] = session('uid');
        $info['request_id'] = I('post.request_id');
        $where['teamname'] = I('post.teamname');
        $info['type'] = 2;
        $info['teamid'] = M('ExpertTeam')->where($where)->getField('teamid');
        $info['title'] = "来自".I('post.teamname')."的邀请";
        $info['content'] = I('post.content');
        $info['remark'] = I('post.remark');
        $result = M('RequireLink')->add($info);
        if($result){
            $this->success("邀请发布成功!",U('Expert/expertlist'));
        }else{
            $this->error("邀请失败，请稍后再试",U('Expert/expertlist'));
        }
    }
    
}
?>