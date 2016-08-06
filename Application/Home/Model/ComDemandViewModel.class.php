<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * 企业需求模块
 * 作用：
 *	将发布需求的企业信息和需求对应
 * 	sql like folloewing :
 * 	select * from login,deamnd,company_user where demand_id=uid and uid=id
 * 		
 */
class ComDemandViewModel extends ViewModel {
	public $viewFields = array(
		'demand'=>array('demand_id','title','demand_type','demand_com','total_info','tech_target','other_info','publish_time','status','sort'),
		'company_user'=>array('uid','city','area','research','info','linkman','website', '_on'=>'demand.demand_com=company_user.uid'),
		'city'=>array('id','cityname','_on'=>'id=company_user.city')
		);
}
?>
