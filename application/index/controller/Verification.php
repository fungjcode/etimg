<?php
namespace app\index\controller;
use \think\Cache;
use \think\Controller;

class Verification extends Controller {
	//空方法
	public function _empty() {
		return json(['code' => '0', 'data' => ['msg' => 'error']]);
	}

	//空页面
	public function _404() {
		return $this->fetch('404');
	}

	//设置用户验证信息
	/*解决两个问题，一是设置用户的缓存信息，二是设置用户如果登录更新一开始的用户缓存信息
	*/
	public function setusertemp($userip, $logstat) {
		//缓存名称,IP+usertemp
		$tempname = $userip . 'usertemp';
		//用户登录状态
		if (empty($logstat)) {
			$logstat = 0; //未登录
		} else {
			$logstat = 1; //登录
		}
		//判断是否有缓存
		if (empty(Cache::get($tempname))) {
			//创建缓存
			//制作一个和IP地址绑定的KEY
			$userkey = md5(time());
			//根据IP地址判断缓存，缓存为24小时
			Cache::set($tempname, array('userkey' => $userkey, 'userip' => $userip, 'logstat' => $logstat), 86400);
		}
	}

	//上传数量初始化
	public function uploadnum($userip, $logstat) {
		$tempup = Cache::get($userip . 'uploadnum');
		if (empty($tempup)) {
			//缓存名称,IP+uploadnum
			$tempname = $userip . 'uploadnum';
			//判断是否有缓存
			if (empty(Cache::get($tempname))) {
				//初始化0次
				Cache::set($tempname, 0, 86400);
			}
		}
	}

}