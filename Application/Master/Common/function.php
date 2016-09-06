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
?>