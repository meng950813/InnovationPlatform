<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * create by cm
 * 企业需求模块
 * 作用：
 *	将发布需求的企业信息和需求对应
 * 	sql like folloewing :
 * 	select * from login,deamnd,company_user where demand_id=uid and uid=id
 * 		
 */
class ComDemandListViewModel extends ViewModel {
	public $viewFields = array(
		'demand'=>array('demand_id','title','demand_type','demand_com','publish_time','status','sort'),
		'login'=>array('id','realname', '_on'=>'demand.demand_com=login.id'),
		);
}
?>
