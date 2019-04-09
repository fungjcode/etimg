<?php
namespace app\index\controller;
use \think\Cache;
use \think\Controller;
use \think\Db;

class Image extends Controller {
	//空方法
	public function _empty() {
		return json(['code' => '0', 'data' => ['msg' => 'error']]);
	}

	//空页面
	public function _404() {
		return $this->fetch('404');
	}

	//图片信息
	public function imginfo($loaclpath) {
		$image = \think\Image::open($loaclpath);
		if ($image) {
			// 返回图片的宽度
			$width = $image->width();
			// 返回图片的高度
			$height = $image->height();
			// 返回图片的类型
			$type = $image->type();
			// 返回图片的mime类型
			$mime = $image->mime();
			// 返回图片的尺寸数组 0 图片宽度 1 图片高度
			$size = $image->size();
			//组成一个数组
			$info = ['width' => $width, 'height' => $height, 'type' => $type, 'mime' => $mime];
			return $info;
		} else {
			return false;
		}
	}

	//图片压缩
	public function imgthumb($loaclpath, $filename) {
		//缩略图存储区域
		$thumbpath = ROOT_PATH . 'public' . DS . 'uploads' . DS . date("Y-m-d") . DS . 'thumb' . DS; //存储路径
		if (!is_dir($thumbpath)) {
			mkdir($thumbpath);
		}
		$thumbname = 'thumb-' . $filename;
		$image = \think\Image::open($loaclpath);
		// 按照原图的比例生成一个最大为320*320的缩略图并保存为thumb.png，按等比例
		$image->thumb(400, 400, \think\Image::THUMB_SCALING)->save($thumbpath . $thumbname);
		if (is_file($thumbpath . $thumbname)) {
			//返回本地缩略图路径
			return 'public' . DS . 'uploads' . DS . date("Y-m-d") . DS . 'thumb' . DS . $thumbname;
		} else {
			return false;
		}
	}

	//图片上传
	public function uploadimg($imagefile, $uptempnum) {
		$file = request()->file($imagefile);
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
			//图片信息获取
			$imginfo = $this->imginfo($loaclpath); //图片信息,数组
			//图片压缩
			$imgthumb = $this->imgthumb($loaclpath, $filename);
			//图片鉴黄分图片等级
			/*$api = controller('index/Api', 'controller');
			$moderatecontent = $api->moderatecontent($imgthumb);*/
			//上传数量自增,步进值为1,20次以后不能上传
			Cache::inc($uptempnum);
		} else {
			return $file->getError();
		}
	}

	//图片删除
	public function delimg($imgid) {
		$imgid = $imgid;
		//查询数据库
		$controllerimg = controller('index/Mysql', 'controller');
		$delimg = $controllerimg->deldb($imgid);
		if ($delimg != false) {
			//删除本地图片
			//大图
			$bigimg = ROOT_PATH . DS . $delimg['loaclpath'];
			//小图
			$smallimg = ROOT_PATH . DS . $delimg['thumbpath'];
			$delbig = unlink($bigimg);
			$delsmallimg = unlink($smallimg);
			//删除数据库
			$deldb = Db::name('imginfo')->where('imgurl', $imgid)->delete();
			if ($deldb) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	//图片等级验证
	/*
	该项功能为自动执行
	*/
	public function aouthimgleave() {
		//查询数据库里面还未分级的图片
		$mysql = controller('index/Mysql', 'controller');
		$findleave = $mysql->findleave();
		if ($findleave != false) {
			$imgthumb = $findleave['thumbpath'];
			//图片鉴黄分图片等级
			$api = controller('index/Api', 'controller');
			$moderatecontent = $api->moderatecontent($imgthumb);
			if ($moderatecontent != false) {
				//更新数据库状态为图片等级的值
				Db::name('imginfo')->where('imgurl', $findleave['imgurl'])->update(['imglave' => $moderatecontent]);
			} else {
				exit();
			}
		} else {
			exit();
		}
	}

}