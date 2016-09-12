<?php
namespace Master\Controller;
use Lib\MasterController;

class ClassicCaseController extends MasterController {
	public function case_list(){
		$ClassicCase = M('ClassicCase'); // 实例化对象
		$num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $ClassicCase->count();// 查询满足要求的总记录数
		$Page = new \Lib\Page($count,$num);//化分页类入总记录数和每页显示的记录数
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();// 分页显示输出

		$list = $ClassicCase->page(I('get.p','1').",$num")->order('sort desc')->select();
		
		$this->list = $list;// 赋值数据集
		$this->page = $show;// 赋值分页输出

		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['case_id'] = I('get.id');
			$result = M('ClassicCase')->where($where)->delete();
			if($result){
				$this->success('删除成功');
			}
			else{
				$this->error('删除失败，请稍后再试');
			}
		}
		else{
			$this->error('请选择删除对象');
		}
	}
	public function add_modify(){
		if(I('get.id')){
			$where['case_id'] = I('get.id');
			$info = M('ClassicCase')->where($where)->find();
			$cp = M("CasePicture")->where($where)->find();
			$this->info = $info;
			$this->picture = $cp['picture'];
		}
		
		$this->display();
	}

	public function deal_cases(){
		/* 获取表单全部信息，使用这种方法要保证表单中的变量名与数据库中的字段名一致。
		使用单独获取会导致 富文本 中的内容 以字符串形式存储，标签也会被转义存储，无法正常显示
		*/
		$caseInfo=$_POST;

		// 获取当前日期
		$caseInfo['publish_time'] = date('Y-m-d');
		/* 获取当前时间 */
	    $case['sort'] = date('Y-m-d H:i:s',time());
		
		$pictureResult='';
		// 传值有id ，修改数据
	    if(!empty(I('post.case_id'))){
	    	$where['case_id'] = I('post.case_id');

	    	if (!empty($_FILES)) {//有图片上传
	    		$CasePicture = M("CasePicture");
	            // 返回值不为null -> 上传成功
	            if(($result = newsPicture($_FILES,'case_picture',300,200))){
	            	$picture['case_id'] = $where['case_id'];

					$pictureInfo['picture'] = $result;
	            	/* 判断之前是否已有图片 */
	            	if($CasePicture->where($picture)->find()){
	            		/* 上传过图片，更新 */
	                	$pictureResult = $CasePicture->where($picture)->data($pictureInfo)->save();
	            	}
	            	else{
	            		/*  */
	                	$pictureResult = $CasePicture->data($picture)->add();
	            	}
	                if(!$pictureResult){
	                	$this->error("图片上传失败，请稍后重试");
	                }
	            }
        	}

	    	$result = M('ClassicCase')->where($where)->data($caseInfo)->save();

	    }
	    // 添加数据
	    else{
	    	if (!empty($_FILES)) {//有图片上传
	            // 返回值不为null -> 上传成功
	            if(($result = newsPicture($_FILES,'case_picture',300,200))){
	            	$picture['case_id'] = $where['case_id'];
	                $picture['picture'] = $result;
	                $pictureResult = M('CasePicture')->data($picture)->add();
	                var_dump($pictureResult);
	                if(!$pictureResult){
	                	$this->error("图片上传失败，请稍后重试");
	                }
	            }
        	}

	    	$result = M('ClassicCase')->data($caseInfo)->add();
	    }
	    
	    if($result||$pictureResult){
	    	$this->success("操作成功",U('Master/ClassicCase/case_list'));
	    }
	    else{
	    	$this->error("操作失败，请稍后重试");
	    }
		
	}
}
