<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 科研成果
 */
class ResultController extends Controller {

	/**
	 * 科技成果列表页
	 *
	 * @return [type] [description]
	 */
    public function resultlist(){
    	$this->display();
    }

    /**
     * 详情页
     *
     * @return [type] [description]
     */
    public function result(){
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
        $info['research_type'] = getResearchType(I('post.research_type'));

        if(I('post.isteam') == 1){
            $where['teamname'] = I('post.team_name');
            $info['owner_id'] = M('ExpertTeam')->where($where)->field('teamid')->postField();
            $info['isteam'] = 1;
        }
        else{
           $info['owner_id'] = session('uid'); 
           $info['isteam'] = 0;
        }

        $info['end_time'] = I('post.end_time');
        $info['research_level'] = I('post.research_level');
        $info['total_info'] = I('post.total_info');
        $info['tech_target'] = I('post.tech_target');
        $info['other_info'] = I('post.other_info');
        if (!empty($_FILES)) {//有图片上传
            // 返回值不为null -> 上传成功
            if(($result = uploadPicture($_FILES,'research_picture'))){
                $info['research_picture'] = $result;
            }
        }
        $result = M('ResearchResult')->date($info)->add();
        if($result){
            $this->success("发布成功",U('Result/resultlist'));
        }
        else{
            $this->error("发布失败，请稍后重试",U('Result/resultlist'));
        }
    }
}
?>