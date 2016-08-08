<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 企业需求
 */
class DemandController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function demandlist(){
        $where['status']=1;
        if(isset($_GET['type'])){
            echo "string";
            $type = getResearchType($_GET['type']);
            $where['demand_type'] = array("like","%".$type);
        }
        $list = M('Demand')->where($where)->order('sort desc')->page($_GET['p'].',20')->select();;
        $count = M('Demand')->where($where)->count();
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
        if(I('get.id')&&I('get.com')){
            $info['uid'] = I('get.com');
            $comInfo = D('ComInfoView')->where($info)->find();
            $where['demand_id'] = I('get.id');
            $demandInfo = M("Demand")->where($where)->find();
            // 用户已登录
            if(session('uid')){
                // 不是发布者本身查看
                if (session('uid') != I('get.com')) {
                     // isLinked函数，判断双方是否已建立联系
                    if(!isLinked(session('uid'),$info['uid'],3)){
                        // 若当前用户与企业未建立联系，将联系信息置为 ******
                        $comInfo['mobile_phone']    = "********";
                        $comInfo['email']           = "********";
                        $comInfo['website']         = "********";
                        $comInfo['area']            = "********";
                        $comInfo['linkman']         = "********";
                        $comInfo['link_phone']      = "********";
                        $comInfo['linkEmail']       = "********";
                        $comInfo['jobname']         = "********";
                    }
                }
            }
            // 未登录的游客
            else{
                $comInfo['mobile_phone']    = "********";
                $comInfo['email']           = "********";
                $comInfo['website']         = "********";
                $comInfo['area']            = "********";
                $comInfo['linkman']         = "********";
                $comInfo['link_phone']      = "********";
                $comInfo['linkEmail']       = "********";
                $comInfo['jobname']         = "********";
            }
            $this->assign("comInfo",$comInfo);
            $this->assign("demandInfo",$demandInfo);

        }
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
        if(session('uid')){
            $where['demand_com'] = session("uid");
            if(M('Demand')->add($where)){
                $this->success("test success");
            }
            else{
                $this->error("test error");
            }
            $info['demand_com'] = session('uid');

            $info['title'] = $_POST['demand_title'];
            $info['total_info'] = I('post.total_info',"");
            $info['tech_target'] = I('post.tech_target',"");
            $info['other_info'] = I('post.other_info',"");
            $info['publish_time'] = date('Y-m-d');
            $info['end_time'] = I('post.end_time');
            $checkbox = getResearchType(I('post.demand_type'));
            if($checkbox){
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
        }else{
            $this->error("请先登录",U('Public/login'));
        }
    }
}
?>