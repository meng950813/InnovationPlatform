<?php
namespace Lib;
use Think\Controller;
class MasterController extends Controller {
	public function _initialize(){

		if(session('power') != 'admin'){
			$this->redirect('Master/Public/login');
			return;
		}
		$this->act = CONTROLLER_NAME;
	}
}
