<?php
namespace app\index\controller;
use \think\Cache;
use \think\Controller;
use \think\Db;
use \think\Request;

class Index extends Controller {
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

	//主页
	public function index() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//获取当前用户IP
			$request = Request::instance();
			$userip = $request->ip();
			//获取当前用户登录信息
			$logstat = Cache::get('username');
			//设置用户登录缓存信息
			//调用控制器
			$verification = controller('index/Verification', 'controller');
			$verification->setusertemp($userip, $logstat);
			//上传数量
			$verification->uploadnum($userip, $logstat);
			if ($userip) {
				//读取公告
				$noticedb = Db::name('notice')->where('')->order('id desc')->cache(true, 86400)->find();
				//模板
				//用户状态
				if (!empty($logstat)) {
					$this->assign('userinfo', '0');
				} else {
					$this->assign('userinfo', $logstat);
				}
				$this->assign('webname', $webinfo['webname']);
				$this->assign('logo', $webinfo['webname']);
				$this->assign('description', $webinfo['description']);
				$this->assign('keywords', $webinfo['keywords']);
				$this->assign('weburl', $webinfo['weburl']);
				$this->assign('webleave', 'index');
				$this->assign('notice', $noticedb['info']);
				return view();
			} else {
				return json(['code' => '0', 'data' => ['msg' => 'error']]);
			}
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//图片完成的页面
	public function i() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//正常
			//获取当前用户IP
			$request = Request::instance();
			$userip = $request->ip();
			//缓存的IP
			$tempid = Cache::get($userip . 'usertemp');
			if ($userip == $tempid['userip']) {
				//正常
				//上传限制判断，游客每天20张，会员1W张
				if ($tempid['userip'] == 0) {
					$uploadnum = 20;
				} else {
					$uploadnum = 10000;
				}
				//上传次数
				$uptempnum = Cache::get($userip . 'uploadnum');
				if ($uptempnum <= $uploadnum) {
					//正常
					//上传图片
					$file = request()->file('image');
					if (!empty($file)) {
						//上传的目录
						$path = ROOT_PATH . 'public' . DS . 'uploads' . DS . date("Y-m-d") . DS; //存储路径
						//创建文件夹
						if (!is_dir($path)) {
							mkdir($path);
						}
						//文件类型判断,上传大小是5242880,5M,下面是字节为单位
						$info = $file->validate(['size' => 5242880, 'ext' => 'jpg,png,gif,bmp'])->rule('md5')->move($path);
						if ($info) {
							//文件存储位置，文件名，图片鉴黄，创建时间等信息
							$filename = $info->getFilename(); //文件名
							$filetype = $info->getExtension(); //文件类型
							$filepath = $info->getSaveName(); //文件存储具体路径，加上PATH就是完整路径
							$loaclpath = 'public' . DS . 'uploads' . DS . date("Y-m-d") . DS . $filepath; //图片完整地址，没有域名
							$controllerimg = controller('index/Image', 'controller');
							//图片信息获取
							$imginfo = $controllerimg->imginfo($loaclpath); //图片信息,数组
							//图片压缩
							$imgthumb = $controllerimg->imgthumb($loaclpath, $filename);

							//上传数量自增,步进值为1,20次以后不能上传
							Cache::inc($userip . 'uploadnum');
							//整合所有信息
							//生成一个图片ID
							$urlid = substr(md5(time()), 1, 16);
							$arr1 = [
								'filename' => $filename, //文件名
								'ctime' => time(), //创建时间
								'loaclpath' => $loaclpath, //本地文件路径
								'thumbpath' => $imgthumb, //缩略图存储位置
								'userip' => ip2long($userip), //用户IP，遍历写入数据库
								'username' => '', //用户名，下一步开发会员需要使用
								'userlave' => '0', //用户等级
								'imgurl' => $urlid, //图片URL
							];
							$allinfo = array_merge($arr1, $imginfo);
							//把相关信息写入数据库
							$mysql = controller('index/Mysql', 'controller');
							$addsql = $mysql->addsql($allinfo);
							if ($addsql) {
								//数据添加正常
								$this->redirect(url('/u/' . $urlid));
							} else {
								//这里需要删除已经上传的图片，节约服务器空间
								$this->error('程序出了点小问题，请重新上传', '/');
							}
						} else {
							$this->error($file->getError(), '/');
						}
					} else {
						$this->error('您还没有选择要上传的图片哦', '/');
					}
				} else {
					$this->error('您今天已经上传了20张图片，注册会员无限制哇', '/');
				}
			} else {
				$this->error('用户信息验证失败', '/');
			}

		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//图片展示
	public function u() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			$request = Request::instance();
			$url = $request->url(true);
			$urlid = input('id');
			if (!empty($urlid)) {
				//数据库读取
				$mysql = controller('index/Mysql', 'controller');
				$findurlid = $mysql->findurlid($urlid);
				if ($findurlid != false) {
					//正常
					$this->assign('webname', '图片查看 - ' . $webinfo['webname']);
					$this->assign('logo', $webinfo['webname']);
					$this->assign('description', $webinfo['description']);
					$this->assign('keywords', $webinfo['keywords']);
					$this->assign('weburl', $webinfo['weburl']);
					$this->assign('dbinfo', $findurlid);
					$this->assign('webleave', 'l');
					$this->assign('localurl', $url);
					$this->assign('imgurl', $webinfo['weburl'] . DS . $findurlid['loaclpath']);
					$this->assign('thumbimgurl', $webinfo['weburl'] . DS . $findurlid['thumbpath']);
					return view();
				} else {
					$this->error('没有找到这张图片...', '/');
				}
			} else {
				$this->error('非法操作', '/');
			}
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//随便看看
	public function l() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			// 查询状态为1的用户数据 并且每页显示10条数据
			$list = Db::name('imginfo')->where('imglave != 3')->order('id desc')->cache(true, 900)->paginate(16);
			if ($list) {
				// 获取分页显示
				$page = $list->render();
				// 模板变量赋值
				$this->assign('webname', '图片查看 - ' . $webinfo['webname']);
				$this->assign('logo', $webinfo['webname']);
				$this->assign('description', $webinfo['description']);
				$this->assign('keywords', $webinfo['keywords']);
				$this->assign('weburl', $webinfo['weburl']);
				$this->assign('webleave', 'u');
				$this->assign('list', $list);
				$this->assign('page', $page);
				// 渲染模板输出
				return $this->fetch();
			} else {
				$this->error('该功能维护中，请稍后访问', '/');
			}
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

	//关于我们页面
	public function about() {
		$webinfo = $this->webinfo();
		if ($webinfo['webstate'] != 0) {
			//直接模板
			// 模板变量赋值
			$this->assign('webname', '关于我们 - ' . $webinfo['webname']);
			$this->assign('logo', $webinfo['webname']);
			$this->assign('description', $webinfo['description']);
			$this->assign('keywords', $webinfo['keywords']);
			$this->assign('weburl', $webinfo['weburl']);
			$this->assign('webleave', 'about');
			// 渲染模板输出
			return $this->fetch();
		} else {
			return json(['code' => '0', 'data' => ['msg' => 'Close down']]);
		}
	}

}
