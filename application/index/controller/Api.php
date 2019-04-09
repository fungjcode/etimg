<?php
namespace app\index\controller;
use \think\Controller;

class Api extends Controller {
	//空方法
	public function _empty() {
		return json(['code' => '0', 'data' => ['msg' => 'error']]);
	}

	//空页面
	public function _404() {
		return $this->fetch('404');
	}

	//图片鉴黄
	public function moderatecontent($imgthumb) {
		$fileurl = 'http://c.etimg.net' . DS . $imgthumb;
		$key = \think\Config::get('webconfig.moderatecontent_key');
		//POST的接口地址
		$post = "https://www.moderatecontent.com/api/v2?key=$key&url=$fileurl";
		$apiinfo = file_get_contents($post, true);
		$arrinfo = json_decode($apiinfo);
		$apicode = $arrinfo->error_code; //接口返回的代码，0是正常
		if ($apicode == 0) {
			//图片等级1是全部OK，2是青年，3是色色情
			//返回检测数据
			$rating_index = $arrinfo->rating_index;
			return $rating_index;
		} else {
			\think\Log::write('图片鉴黄接口错误，返回的错误代码是：' . $apicode . '，具体错误信息参考https://www.moderatecontent.com/documentation/content', 'Api-moderatecontent');
			return false;
		}
	}
}
