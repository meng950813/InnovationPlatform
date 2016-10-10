<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 教育培训
 */
class EduController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function edulist(){
        $where['status'] = 1;
        if(!empty(I('get.type'))){
            switch (I('get.type')){
                case 1:
                    //资格教育
                    $where['type'] = 1;
                    break;
                case 2:
                    // 技能培训
                    $where['type'] = 2;
                    break;
                default:
                    //学历教育
                    $where['type'] = 0;
                    break;
            }
        }

        $Edu =  M('EduTrain');
        $list =$Edu->where($where)->order('sort desc')->page(I('get.p',1).',20')->field('edu_id,title,publish_time,click')->select();
     
        // 分页
        $count = $Edu->where($where)->count();
        $page = new \Think\Page($count,20);
        $show = $page->show();

        $this->assign('type',I('get.type'));
        $this->assign('list',$list);
        $this->assign('page',$show);
    	$this->display();
    }

    /**
     * 详情页
     *
     */
    public function edu(){
        $where['edu_id'] = I('get.id');
        $Edu =  M('EduTrain');

        // 点击量加 1
        $Edu->where($where)->setInc('click');
        $info = $Edu->where($where)->find();

        // 下一个
        // $next_info['type'] = $info['type'];
        $next_info['edu_id'] = array('lt',$info['edu_id']);
        $next = $Edu->order("edu_id desc")->where($next_info)->field('edu_id,title')->find();
        // 转变换行符
        // $next['content'] = replace_br($info['content']);

        // 上一个
        // $past_info['type'] = $info['type'];
        $past_info['edu_id'] = array('gt',$info['edu_id']);
        $past = $Edu->order("edu_id")->where($past_info)->field('edu_id,title')->find();

        $this->assign('info',$info);
        $this->assign('next',$next);
        $this->assign('past',$past);
    	$this->display();
    }
}
?>