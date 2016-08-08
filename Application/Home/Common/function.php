<?php
	/**
	* 将获取的研究方向的值对应成相应名称
	*
	*/
	function getResearchType($arr){
		$size = count($arr);
		if($size < 1){
			return null;
		}
        $type;
        for ($i=0; $i < $size; $i++) { 
            switch ($arr[$i]) {
                case 1:
                    $type = $type.'电子信息';
                    break;
                case 2:
                    $type = $type.'机械设计与制造';
                    break;
                case 3:
                    $type = $type.'新能源与环保';
                    break;
                case 4:
                    $type = $type.'生物技术与医药';
                    break;
                case 5:
                    $type = $type.'新材料';
                    break;
                case 6:
                    $type = $type.'现代农业';
                    break;
                default:
                    $type = $type.'其他';
                    break;
            }
            if($i < ($size-1)){
                $type = $type.',';
            }
        }
        return $type;
	}

	/**
	*对应相对的职称
	*/
	function getJobType($type){
		$jobtype;
		switch ($type) {
				case 1:
					$jobtype = "教授";
					break;
				case 2:
					$jobtype = "副教授";
					break;
				case 3:
					$jobtype = "研究员";
					break;
				case 4:
					$jobtype = "副研究员";
					break;
				case 5:
					$jobtype = "高级工程师";
					break;
				case 6:
					$jobtype = "讲师";
					break;
				case 7:
					$jobtype = "其他类正高级";
					break;
				case 8:
					$jobtype = "其他类副高级";
					break;
				case 9:
					$jobtype = "中级";
					break;
				default:
					$jobtype = "初级及以下";
					break;
			}
		return $jobtype;
	}

	/**
	 * 处理上传单张图片函数
	 *
	 * @param [type] $picture 上传的图片文件
	 *
	 * @return [type] $dir 上传文件夹的名称
	 */
	function uploadPicture($picture,$dir){
		$upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath  =     '/'.$dir.'/'; // 设置附件上传目录
        // 这里要保证所有上传图片的input标签的 name = img
        $result   =   $upload->uploadOne($_FILES['img']);// 上传单个文件

        if($result) {//上传成功
            return 'Uploads'.$result['savepath'].$result['savename'];
        } else {
            return null;
        }
	}

	/**
	 * 判断双方是否已建立联系
	 *
	 * @param [type] $require 用户1
	 * @param [type] $request 用户2
	 * @param [type] $type  被联系人的类型，个人->1, 团队->2, 企业->3
	 * @return [type] 
	 */
	function isLinked($require,$request,$type){
		$where = array(
				array(
					'require_id' => $require,
					'request_id' => $request,
					'request_type' =>$type,
					'_logic'=>'and'
					),
				array(
					'require_id' => $request,
					'request_id' => $require,
					'request_type' =>$type,
					'_logic'=>'and'
					),
				'_logic'=>'or'
			);
		return M('Linked')->where($where)->find();
	}
?>