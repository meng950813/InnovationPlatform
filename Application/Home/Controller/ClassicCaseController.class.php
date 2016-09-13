<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 典型案例
 */
class ClassicCaseController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function caselist(){
        $where['status'] = 1;
       
        $ClassicCase =  M('ClassicCase');
        $list =$ClassicCase ->where($where)->order('sort desc')->page(I('get.p',1).',20')->field('case_id,case_title,publish_time')->select();
       
        // 分页
        $count = $ClassicCase->where($where)->count();
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
    public function cases(){
        $where['case_id'] = I('get.id');
        $ClassicCase = M('ClassicCase');


        // 点击量加 1
        $ClassicCase->where($where)->setInc('click');
        $info = $ClassicCase->where($where)->find();

        // 下一个
        $next_info['type'] = $info['type'];
        $next_info['case_id'] = array('gt',$info['case_id']);
        $next = $ClassicCase->order('case_id desc')->where($next_info)->field('case_id,case_title')->find();
        // 转变换行符
        
        // 上一个
        $next_info['case_id'] = array('lt',$info['case_id']);
        $past = $ClassicCase->order('case_id desc')->where($next_info)->field('case_id,case_title')->find();

        $this->assign('info',$info);
        $this->assign('next',$next);
        $this->assign('past',$past);
        
    	$this->display();
    }
}
?>