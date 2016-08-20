<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * create by cm
 * 专家信息模块
 * 作用：
 *	筛选出将login表中的专家用户；
 * 	将其对应的信息从expert_user 表中取出整合到一起；
 * 	sql like folloewing :
 * 	select * from login,expert_user where login.type=1 and login.id=expert_user.uid
 * 		
 */
class ExpertInfoViewModel extends ViewModel {
	public $viewFields = array(
		'login'=>array('id','username','logo', 'realname','type','mobile_phone','email'),
		'expert_user'=>array('uid','identify','birthday','gender', 'work_college','jobname','telphone',
			'political_status','level','degree','research','research_content','info','setup_time', '_on'=>'id=uid')
		);
}
?>
