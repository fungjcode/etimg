<?php

namespace app\index\controller;
use \think\Cache;
use \think\Controller;
use \think\Db;
use \think\Loader;

class Sysadmin extends Controller {
	//空方法
	public function _empty() {
		return json(['code' => '0', 'data' => ['msg' => 'error']]);
	}

	//空页面
	public function _404() {
		return $this->fetch('404');
	}

	//站点信息获取
	public function webinfo() {
		$webdb = Db::name('webinfo')->where('')->cache(true, 3600)->find();
		if ($webdb) {
			return $webdb;
		} else {
			return false;
		}
	}

	//登录页面
	public function adminlogin() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			$postinfo = input('post.');
			$res = '';
			if (!empty($postinfo)) {
				//验证数据
				$validate = Loader::validate('Sysadmin');
				$res = $validate->check($postinfo);
				if ($res == true) {
					//数据验证成功
					//进入后台,写入缓存
					Cache::set('sysadmin', ['username' => input('post.username'), 'password' => md5(input('post.password'))], 3600);
					$this->success('登录成功', 'sysadmin/index');
				} elseif ($res == false) {
					//数据验证失败
					//模板
					$this->assign('error', $validate->getError());
					$this->assign('webname', $webinfo['webname']);
					return view();
				}
			} else {
				//模板
				$this->assign('error', 1);
				$this->assign('webname', $webinfo['webname']);
				return view();
			}

		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//管理后台主页
	public function index() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//获取缓存
			$usertmp = Cache::get('sysadmin');
			//对比数据库
			$mysql = controller('index/Mysql', 'controller');
			$ceadminuser = $mysql->ceadminuser($usertmp['username'], $usertmp['password']);
			if ($ceadminuser == true) {
				//正常
				//模板
				//图片总数
				$imgnum = Db::name('imginfo')->cache(true, 1800)->count('id');
				$this->assign('imgnum', $imgnum);
				$this->assign('webname', $webinfo['webname']);
				return view();
			} else {
				Cache::rm('sysadmin');
				$this->error('登录失败', '/sysadminlogin');
			}
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//退出系统
	public function logout() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//删除缓存
			Cache::rm('sysadmin');
			$this->success('您已经退出系统，欢迎下次再见', '/sysadminlogin');
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//图片管理
	public function imgedit() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//图片显示，分页
			// 查询状态为1的用户数据 并且每页显示10条数据
			$list = Db::name('imginfo')->where('')->order('id desc')->paginate(15);
			// 获取分页显示
			$page = $list->render();
			$this->assign('webname', '图片管理 - ' . $webinfo['webname']);
			$this->assign('list', $list);
			$this->assign('page', $page);
			return view();
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//图片删除
	public function delimg() {
		$imgid = input('imgid');
		if (!empty($imgid)) {
			$mysql = controller('index/Image', 'controller');
			$delimg = $mysql->delimg($imgid);
			if ($delimg == true) {
				$this->success('图片删除成功', 'sysadmin/imgedit');
			} else {
				$this->error('图片删除失败', 'sysadmin/imgedit');
			}
		} else {
			$this->error('参数丢失，删除失败', 'sysadmin/index');
		}
	}

	//高危图片管理
	public function highrisk_img() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//图片显示，分页
			// 查询状态为1的用户数据 并且每页显示10条数据
			$list = Db::name('imginfo')->where('imglave > 1')->order('id desc')->paginate(15);
			// 获取分页显示
			$page = $list->render();
			$this->assign('webname', '图片管理 - ' . $webinfo['webname']);
			$this->assign('list', $list);
			$this->assign('page', $page);
			return view();
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//图片等级修改
	public function reimglv() {
		$imgid = input('imgid');
		if (!empty($imgid)) {
			//调整图片等级
			$mysql = controller('index/Mysql', 'controller');
			$dbreimglv = $mysql->reimglv($imgid);
			if ($dbreimglv != false) {
				$this->success('图片等级恢复成功');
			} else {
				$this->error('图片等级恢复失败');
			}
		} else {
			$this->error('参数丢失，恢复失败', 'sysadmin/index');
		}
	}
}
