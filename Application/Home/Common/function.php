<?php
	/**
	* create by cm
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
	 * @param [type] $request 用户2 或者是 团队 id
	 * @param [type] $type  联系的类型，0：个人-个人,  1：个人- 团队
	 * @return [type] 
	 */
	function isLinked($require,$request,$type){
		// 如果 $require 为 null。即没有登录，直接返回 false
		if(!$require){
			return false;
		}
		// 个人联系团队
		if($type == 1){
			$where = array(
					'require_id' => $require,
					'teamid' => $request,
					'link_type' => 1,
					'_logic'=>'and'
				);
		}
		else{
			// 个人间的联系
			$where = array(
				array(
					'require_id' => $require,
					'request_id' => $request,
					'link_type'  => 0,
					'_logic'=>'and'
					),
				array(
					'require_id' => $request,
					'request_id' => $require,
					'link_type'  => 0,
					'_logic'=>'and'
					),
				'_logic'=>'or'
			);
		}
		$result = M('Linked')->where($where)->find();
		if ($result) {
			return true;
		}
		else{
			return false;
		}
	}

	/**
	* 判断是否已登录
	*/
	function islogin(){
		if(session('uid')){
			return true;
		}
		else{
			$this->error("请先登录",U('Public/login'));
		}
	}

	/**
	* 将require——link表中的status 置为0，表示信息已处理
	*/
    function setStatus($id){
    	$where['id'] = $id;
    	$data['status'] = 0;
    	$result = M('RequireLink')->where($where)->save($data);
    	if ($result) {
    		// 修改成功
    		return true;
    	}
    	return false;
    }

    /**
	* 将需求详情页中的联系信息置为 * ，不具有普适性
	*/
    function demand_none($comInfo){
    	$comInfo['mobile_phone']    = "********";
        $comInfo['email']           = "********";
        $comInfo['website']         = "********";
        $comInfo['area']            = "********";
        $comInfo['linkman']         = "********";
        $comInfo['link_phone']      = "********";
        $comInfo['linkEmail']       = "********";
        $comInfo['jobname']         = "********";
        return $comInfo;
    }

    /**
	* 将团队详情页中的联系信息置为 * ，不具有普适性
	*/
    function team_none($leader){
    	$leader['realname']	 	= "********";
        $leader['level'] 		= "********";
        $leader['mobile_phone'] = "********";
        $leader['telphone']  	= "********";
        $leader['email'] 		= "********";
        $leader['work_college'] = "********";
        $leader['jobname']  	= "********";
        return $leader;
    }

	 /**
	* 将从数据库中取出数据中的换行符转换为 浏览器可以处理的换行符，保证显示效果
	*/
	function replace_br($content){
		return str_replace("\n", "<br>", $content);
	}

	// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
	function authcode($str, $seKey='ThinkPHP.CN'){
		$key = substr(md5($seKey), 5, 8);
		$str = substr(md5($str), 8, 10);
		return md5($key . $str);
	}
	
	//获取随机验证码函数\
	function get_code($length=32,$mode=0){
		switch ($mode){
			case '1':
				$str = '123456789';
				break;
			case '2':
				$str = 'abcdefghijklmnopqrstuvwxyz';
				break;
			case '3':
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case '4':
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case '5':
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
				break;
			case '6':
				$str = 'abcdefghijklmnopqrstuvwxyz1234567890';
				break;
			default:
				$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
				break;
		}
		$checkstr = '';
		$len = strlen($str) - 1;
		for ($i = 0; $i < $length; $i++){
			//$num = rand(0, $len);//产生一个0到$len之间的随机数
			$num = mt_rand(0, $len);//产生一个0到$len之间的随机数
			$checkstr .= $str[$num];
		}
		return $checkstr;
	}
	function check_verify($code, $id = '') {
		/*$verify = new \Think\Verify();
		return $verify->check($code, $id);*/
		$seKey='ThinkPHP.CN';
		$key = authcode($seKey).$id;
		// 验证码不能为空
		$secode = session($key);
		if(empty($code) || empty($secode)) {
			return false;
		}
		// session 过期
		if(NOW_TIME - $secode['verify_time'] > 1800) {
			session($key, null);
			return false;
		}

		if(authcode(strtoupper($code)) == $secode['verify_code']) {
			return true;
		}

		return false;
	}

?>