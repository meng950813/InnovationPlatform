<?php
namespace Master\Controller;
use Lib\MasterController;
class IndexController extends MasterController {
	public function index(){
		// 未登录
		if(!session('master_id')){
			$this->redirect('Master/Public/login');
		}
		$this->display();
	}
	public function welcome(){
		$this->display();
	}

	public function ueditor(){
		C('SHOW_PAGE_TRACE',false);
		$data = new \Org\Util\Ueditor();
		echo $data->output();
	}
}
