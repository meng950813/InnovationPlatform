<?php
namespace Home\Controller;
use Think\Controller;

/**
* create by cm
* 企业需求
*/
class DemandController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function demandlist(){
        $where['status']=1;
        if(!empty($_GET['type'])){
            $type = getResearchType($_GET['type']);
            $where['demand_type'] = array("like","%".$type);
        }

        $Demand =  M('Demand');

        $list =$Demand->where($where)->order('sort desc')->page(I('get.p',1).',20')->select();;
        
        // 分页
        $count = $Demand->where($where)->count();
        $page = new \Think\Page($count,20);
        $show = $page->show();
        
        $this->assign('list',$list);
        $this->assign('page',$show);
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function demand(){
        // 获取到需求id
        $info['uid'] = I('get.com');
        $comInfo = D('ComInfoView')->where($info)->find();
        $comInfo['info'] = replace_br($comInfo['info']);

        $where['demand_id'] = I('get.id');
        $demandInfo = M("Demand")->where($where)->find();
        $demandInfo['total_info'] = replace_br($demandInfo['total_info']);
        $demandInfo['tech_info'] = replace_br($demandInfo['tech_info']);
        $demandInfo['other_info'] = replace_br($demandInfo['other_info']);
        // 用户已登录
        if(session('uid')){
            // 不是发布者本身查看
            if (session('uid') != I('get.com')) {
                 // isLinked函数，判断双方是否已建立联系
                if(!isLinked(session('uid'),$info['uid'],0)){
                    // 若当前用户与企业未建立联系，将联系信息置为 ******
                    // 调用特定函数，该函数不具有 普适性
                   $comInfo = demand_none($comInfo);
                }
            }
        }
        // 未登录的游客
        else{
           $comInfo = demand_none($comInfo);
        }
        $this->assign("comInfo",$comInfo);
        $this->assign("demandInfo",$demandInfo);
    	$this->display();
    }

    /**
     * 需求发布页
     */
    public function add(){
        $this->display();
    }

    /**
     * 需求表单处理函数
     */
    public function addDemand(){
        islogin();

        $info['demand_com'] = session('uid');
        $info['title'] = $_POST['demand_title'];
        $info['total_info'] = I('post.total_info',"");
        $info['tech_target'] = I('post.tech_target',"");
        $info['other_info'] = I('post.other_info',"");
        $info['publish_time'] = date('Y-m-d');
        $info['end_time'] = I('post.end_time');
        $demand_type = getResearchType(I('post.demand_type'));
        if($demand_type){
            $info['demand_type'] = $demand_type;
        }
        if (!empty($_FILES)) {//有图片上传
            // 返回值不为null -> 上传成功
            if(($result = uploadPicture($_FILES,'demand_logo'))){
                $info['demand_logo'] = $result;
            }
        }
        $result = M('Demand')->add($info);
        if($result){
            $this->success("需求发布成功",U('Demand/demandlist'));
        }else{
            $this->error("发布失败",U('Demand/demandlist'));
        }
        
    }
}
?>