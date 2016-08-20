<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * create by cm
 * 成员团队信息模块
 * 作用：
 * 	将用户的团队列举出来
 * 		
 */
class UserTeamViewModel extends ViewModel {
	public $viewFields = array(	
		'expert_team'=>array('teamid', 'teamname','teamleader'),
		'team_member'=>array('teamid'=>'team_id','uid','_on'=>'expert_team.teamid = team_member.teamid')
		);
}
?>
