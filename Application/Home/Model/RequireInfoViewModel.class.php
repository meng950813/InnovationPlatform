<?php

namespace Home\Model;
use Think\Model\ViewModel;

/**
* create by cm
* 联系人信息模块
* 作用：
* 	将require——link表中的联系人字段对应的人员信息列举出来，
*/

class RequireInfoViewModel extends ViewModel{
	
	public $viewFields = array(
			'require_link' => array('id'=>'link_id','require_id','request_id','link_type','teamid','status','title','content','remark','sort'),
			'login' => array('id','realname','type','_on'=>'login.id = require_id')
		);
}
	
?>