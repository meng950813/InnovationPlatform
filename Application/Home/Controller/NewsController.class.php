<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 最新资讯
 */
class NewsController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function newslist(){
        $where['status'] = 1;
        if(!empty(I('get.type'))){
            switch (I('get.type')){
                case 1:
                    //市场报告 
                    $where['type'] = 1;
                    break;
                case 2:
                    // 业内资讯
                    $where['type'] = 2;
                    break;
                default:
                    //政策法规 
                    $where['type'] = 0;
                    break;
            }
        }

        $News =  M('News');
        $list =$News ->where($where)->order('sort desc')->page(I('get.p',1).',20')->field('news_id,title,publish_time,click')->select();
       
        // 分页
        $count = $News->where($where)->count();
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
    public function news(){
        $where['news_id'] = I('get.id');
        $News =  M('News');

        // 点击量加 1
        $News->where($where)->setInc('click');
        $info = $News->where($where)->find();

        // 下一个
        $next_info['type'] = $info['type'];
        $next_info['status'] = 1;
        $next_info['news_id'] = array('gt',$info['news_id']);
        $next = $News->where($next_info)->field('news_id,title')->find();
        // 转变换行符
        // $next['content'] = replace_br($info['content']);

        // 上一个
        $next_info['news_id'] = array('lt',$info['news_id']);
        $past = $News->where($next_info)->field('news_id,title')->find();

        $this->assign('info',$info);
        $this->assign('next',$next);
        $this->assign('past',$past);
    	$this->display();
    }
}
?>