<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * 专家团队成员信息模块
 * 作用：
 * 	将专家团队与成员对应
 * 	sql like folloewing :
 * 	select * from team_member,expert_user where expert_team.teamid = team_member.teamid
 * 		
 */
class TeamInfoViewModel extends ViewModel {
	public $viewFields = array(	
		'expert_team'=>array('teamid', 'teamname','team_logo', 'teamleader','team_research','teaminfo'),
		// 'team_member'=>array('teamid','uid','_on'=>'expert_team.teamid = team_member.teamid'),
		);
}
?>
