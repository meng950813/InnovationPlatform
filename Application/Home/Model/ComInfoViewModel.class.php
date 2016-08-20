<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * create by cm
 * 企业信息模块
 * 作用：
 *	筛选出将login表中的企业用户；
 * 	将其对应的信息从company_user 表中取出整合到一起；
 * 	sql like folloewing :
 * 	select * from login,company_user,city where login.type=2 and login.id=ecompany_user.uid and ccomapny_user.city=city.id
 * 		
 */
class ComInfoViewModel extends ViewModel {
	public $viewFields = array(
		'login'=>array('id','logo','type','realname','mobile_phone','email'),
		'company_user'=>array('uid','area','company_type', 'research','info','linkman','jobname','link_phone','website', '_on'=>'id = uid')
		);
}
?>
