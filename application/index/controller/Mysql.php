<?php
namespace app\index\controller;
use \think\Controller;
use \think\Db;

class Mysql extends Controller {
	//空方法
	public function _empty() {
		return json(['code' => '0', 'data' => ['msg' => 'error']]);
	}

	//空页面
	public function _404() {
		return $this->fetch('404');
	}

	//图片文件数据增加
	public function addsql($allinfo) {
		//把所有信息写入到数据库中
		$db = Db::name('imginfo')->insert($allinfo);
		if ($db) {
			return true;
		} else {
			return false;
		}
	}

	//根据URLID读取图片信息
	public function findurlid($urlid) {
		if (!empty($urlid)) {
			$dbinfo = Db::name('imginfo')->where('imgurl', $urlid)->cache(true, 86400)->find();
			if ($dbinfo) {
				return $dbinfo;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//管理员账户对比数据库
	public function ceadminuser($username, $password) {
		$dbinfo = Db::name('sysadmin')->where("username = '$username' and password = '$password'")->find();
		if ($dbinfo) {
			return $dbinfo;
		} else {
			return false;
		}
	}

	//删除前查询图片数据
	public function deldb($imgid) {
		$dbinfo = Db::name('imginfo')->where("imgurl = '$imgid'")->find();
		if ($dbinfo) {
			return $dbinfo;
		} else {
			return false;
		}
	}

	//查询未分级的图片
	public function findleave() {
		$dbinfo = Db::name('imginfo')->where("imglave = '0'")->order('id ASC')->limit(1)->find();
		if ($dbinfo) {
			return $dbinfo;
		} else {
			return false;
		}
	}

	//恢复图片等级为显示
	public function deldb($imgid) {
		$dbinfo =  Db::name('imginfo')->where('imgurl', $imgid)->update(['imglave' => '1']);
		if ($dbinfo) {
			return $dbinfo;
		} else {
			return false;
		}
	}

}