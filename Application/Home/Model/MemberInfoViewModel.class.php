<?php
namespace Home\Model;
use Think\Model\ViewModel;

/**
* create by cm
* 显示团队成员职称信息
*/

class MemberInfoViewModel extends ViewModel{
	
	public $viewFields = array(
		'team_member' => array('teamid','member_name','uid'=>'member_uid'),
		'expert_user' => array('uid','level','research','_on'=>'expert_user.uid = team_member.uid')
		);
}
?>