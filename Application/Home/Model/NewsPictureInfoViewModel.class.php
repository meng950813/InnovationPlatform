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
class NewsPictureInfoViewModel extends ViewModel {
	public $viewFields = array(
		'news'=>array('news_id','title','status', 'sort'),
		'news_picture'=>array('news_id'=>'message_id','picture', '_on'=>'news.news_id = news_picture.news_id')
		);
}
?>
