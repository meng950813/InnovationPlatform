<?php
namespace Home\Controller;
use Think\Controller;

/**
 * create by cm
 * 下载中心
 */
class DownloadController extends Controller {
	/**
	 * 列表页
	 *
	 */
    public function filelist(){
        $where['status'] = 1;
        if(!empty(I("get.id"))){
            $where['file_id'] = I("get.id");
        }
        $DownloadFile =  M('DownloadFile');
        $list =$DownloadFile->where($where)->order('sort desc')->page(I('get.p',1).',20')->select();
     
        // 分页
        $count = $DownloadFile->where($where)->count();
        $page = new \Think\Page($count,20);
        $show = $page->show();

        $this->assign('list',$list);
        $this->assign('page',$show);
    	$this->display();
    }

    public function addDownload_sum(){
        $where['file_id'] = I('get.id');
        M("DownloadFile")->where($where)->setInc('download_sum');
    }
}
?>