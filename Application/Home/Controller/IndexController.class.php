<?php
namespace Home\Controller;
use Think\Controller;

/**
* create by cm
* 首页
*/
class IndexController extends Controller {
    public function index(){
    	$where['status'] = 1;

        /* 新闻图片 */
    	$newsPictureInfo = D('NewsPictureInfoView')->where($where)->order('sort desc')->limit(5)->field('news_id,title,picture')->select();

        /* 新闻列表 */
    	$newsInfo = M("News")->where($where)->order('sort desc')->limit(9)->field('news_id,title')->select();

        /* 科技成果 */
        $resultInfo = M("ResearchResult")->where($where)->order('sort desc')->limit(9)->field('result_id,result_name')->select();

        /* 企业需求 */
        $demandInfo = M("Demand")->where($where)->order('sort desc')->limit(9)->field('demand_id,title,demand_com')->select();

        /* 协同管理 */
        $cooInfo = M("Cooperation")->where($where)->order('sort desc')->limit(9)->field('name,website')->select();

        /* 下载中心 */
        $downloadInfo = M("DownloadFile")->where($where)->order('sort desc')->limit(9)->field('file_id,download_title')->select();

        /* 典型案例 图片 */
        $casePictureInfo = D('CasePictureInfoView')->where($where)->order('sort desc')->limit(5)->field('case_id,case_title,picture')->select();
        
        /* 典型案例 列表 */
        $caseInfo = M("ClassicCase")->where($where)->order('sort desc')->limit(9)->field('case_id,case_title')->select();

        /* 新闻图片 */
    	$this->assign("newsPicture",$newsPictureInfo);

        /* 新闻列表 */
        $this->assign("newsInfo",$newsInfo);
        
        /* 科技成果 */
        $this->assign("resultInfo",$resultInfo);
        
        /* 企业需求 */
        $this->assign("demandInfo",$demandInfo);
        
        /* 协同管理 */
        $this->assign("cooInfo",$cooInfo);
        
        /* 下载中心 */
    	$this->assign("downloadInfo",$downloadInfo);
        
        /* 典型案例 图片 */
        $this->assign("casePicture",$casePictureInfo);
        
        /* 新闻图片 */
        $this->assign("caseInfo",$caseInfo);

    	$this->display();
    }
}
?>