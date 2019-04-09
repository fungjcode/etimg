<?php
namespace app\index\validate;
use think\Validate;
use \think\Db;

class Sysadmin extends Validate {
	protected $rule = [
		'captcha' => 'cecaptcha:captcha',
		'username' => 'ceusername:username',
		'password' => 'cepassword:password',
	];

	protected $message = [
		'captcha' => '验证码错误',
		'username' => '用户名或密码错误',
		'password' => '用户名或密码错误',
	];

	// 验证码验证
	protected function cecaptcha($value, $rule, $data) {
		$captcha = new \think\captcha\Captcha();
		if (captcha_check($value)) {
			return true;
		} else {
			return false;
		}
	}

	//用户名验证
	protected function ceusername($value, $rule, $data) {
		$webdb = Db::name('sysadmin')->where('username', $value)->find();
		if (!empty($webdb)) {
			return true;
		} else {
			return false;
		}
	}

	//密码验证
	protected function cepassword($value, $rule, $data) {
		$webdb = Db::name('sysadmin')->where('password', md5($value))->find();
		if (!empty($webdb)) {
			return true;
		} else {
			return false;
		}
	}

}