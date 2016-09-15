<?php
namespace Master\Controller;
use Lib\MasterController;

class DownloadController extends MasterController {
	public function file_list(){
		$DownloadFile = M('DownloadFile'); // 实例化对象
		$num = C('PAGE_NUM',null,5);//每一页的数量
		$count = $DownloadFile->count();// 查询满足要求的总记录数
		$Page = new \Lib\Page($count,$num);//化分页类入总记录数和每页显示的记录数
		$Page -> setConfig('size', 'pagination');
		$show = $Page->show();// 分页显示输出

		$list = $DownloadFile->page(I('get.p','1').",$num")->order('sort desc')->select();
		$size = count($list);
		for ($i=0; $i < $size; $i++) {
			// explode 函数将字符串拆分 返回数组
			// 使用 end($arr) 或 array_pop($arr) 函数，获取数组的最后一个元素
			$list[$i]['file_name']= array_pop(explode('/',$list[$i]['file_path']));
		}

		$this->list = $list;// 赋值数据集
		$this->page = $show;// 赋值分页输出

		$this->display();
	}

	public function del(){
		if(I('get.id')){
			$where['file_id'] = I('get.id');
			$result = M('DownloadFile')->where($where)->delete();
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
			$where['file_id'] = I('get.id');
			$info = M('DownloadFile')->where($where)->find();
			$info['file_name'] = array_pop(explode('/', $info['file_path']));
			$this->info = $info;
		}
		$this->display();
	}

	public function deal_data(){
		/* 获取表单全部信息，使用这种方法要保证表单中的变量名与数据库中的字段名一致。
		使用单独获取会导致 富文本 中的内容 以字符串形式存储，标签也会被转义存储，无法正常显示
		*/
		
		$info['download_title'] = $_POST['download_title'];

		$info['info'] = I('post.info');
		// 获取当前日期
		$info['publish_time'] = date('Y-m-d');
		if(!empty(I('post.download_files'))){
			$info['file_path'] = I('post.download_files');
		}

		// 传值有id ，修改数据
		if(!empty(I('post.file_id'))){
			$where['file_id'] = I('post.file_id');
			$result = M("DownloadFile")->where($where)->data($info)->save();
		}
		else{
			// 没有id，添加新数据
			$result = M("DownloadFile")->add($info);
		}
		
		if ($result) {
			$this->success("操作成功",U('Master/Download/file_list'));
		}
		else{
			$this->error("操作失败，请稍后再试");
		}
	}
	
	public function uploadFile(){
		C('SHOW_PAGE_TRACE',false);
		$config = array(
			'maxSize'	=>	20971520,// 设置附件上传大小，最大20M  20971520
			'exts'      =>  array('txt','doc','docx','xls','xlsx','ppt','pptx','pdf','md','zip','rar','7z','chm'),// 设置附件上传类型
			'saveName'	=>	'',
			'savePath'  =>  'downloadfile/', // 设置附件上传目录
			'autoSub'	=>	true,  
		);
		$upload = new \Think\Upload($config,'sae');// 实例化适应sae的上传类
		// $upload = new \Think\Upload($config);// 实例化上传类
		// 上传文件
		$info = $upload->upload();

		if(!$info) {
			// 上传错误提示错误信息
			$file['status'] = 0;
			$file['errmsg'] = $upload->getError();
		}else{
			// 上传成功
			$file['status'] = 1;
			$file['path'] = 'Uploads/'.$info['zip']['savepath'].$info['zip']['savename'];
		}
		echo json_encode($file);
	}
}
