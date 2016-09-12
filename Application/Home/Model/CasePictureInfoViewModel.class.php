<?php
namespace Home\Model;
use Think\Model\ViewModel;
/**
 * create by cm
 * 		
 */
class CasePictureInfoViewModel extends ViewModel {
	public $viewFields = array(
		'classic_case'=>array('case_id','case_title','status', 'sort'),
		'case_picture'=>array('case_id'=>'message_id','picture', '_on'=>'classic_case.case_id = case_picture.case_id')
		);
}
?>
