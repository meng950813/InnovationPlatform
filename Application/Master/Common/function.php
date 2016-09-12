<?php 
	/**
	* 判断是否已登录
	*/
	function isMsaterLogin(){
		if(session('master_id')){
			return true;
		}
		else{
			$this->error("请先登录",U('Master/Public/login'));
		}
	}


	/**
	 * 处理上传单张图片函数
	 *
	 * @param [type] $picture 上传的图片文件
	 *
	 * @return [type] $dir 上传文件夹的名称
	 */
	function newsPicture($picture,$dir,$width,$height){
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath  =     '/'.$dir.'/'; // 设置附件上传目录
        // 这里要保证所有上传图片的input标签的 name = img
        $result   =   $upload->uploadOne($_FILES['news_picture']);// 上传单个文件

        if($result) {//上传成功
        	/* 裁剪图片 */
        	$img = new \Think\Image();
        	$imgPath = 'Uploads'.$result['savepath'].$result['savename'];
        	/*打开 图片*/
        	$img->open('./'.$imgPath);
        	// 按照原图的比例生成一个最大为 $width * $height 的缩略图并保存为thumb.jpg
        	$img->thumb($width, $height,\Think\Image::IMAGE_THUMB_FIXED)->save('./'.$imgPath);
        	// echo "newsPicture".$imgPath;
            return $imgPath;
        } else {
            return null;
        }
	}

	/* 处理上传文档 */
	function uploadFile($file){
		C('SHOW_PAGE_TRACE',false);
 		$config = array(
			'maxSize'	=>	20971520,// 设置附件上传大小，最大20M  20971520
			'exts'      =>  array('txt','doc', 'docx', 'xls', 'xlsx','ppt','pptx','pdf','md','zip','rar','7z','chm'),// 设置附件上传类型
			'savePath'  =>  '/downloadfile/', // 设置附件上传目录
			'autoSub'	=>	true,  
		);
		// $upload = new \Think\Upload($config,'sae');// 实例化适应sae的上传类
		$upload = new \Think\Upload($config);// 实例化上传类

		$result = $upload->uploadOne($_FILES['download_files']);
		if($result){
			$array = array(1,'Uploads'.$result['savepath'].$result['savename']);
		}
		else{
			$array = array(0,''.$upload->getError());
		}
		return $array;
	}

?>