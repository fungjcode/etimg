# etimg
thinkphp简单的图床程序
# 使用
1.  修改application目录下*database.php* 文件，将下面内容修改为你的数据库配置信息
    	'hostname' => '127.0.0.1',
    	// 数据库名
    	'database' => 'database',
    	// 用户名
    	'username' => 'username',
    	// 密码
    	'password' => '',

3. 还原db目录中的 ***img.sql*** 数据库文件。

3. 修改application目录下*config.php*。
    	'webconfig' => [
    		'moderatecontent_key' => 'key', //moderatecontent_key
    	],
这里是moderatecontent的图片鉴黄借口，API KEY申请地址为 https://www.moderatecontent.com

4. 修改\application\index\controller目录下 *Api.php* 文件
```php
$fileurl = 'http://c.etimg.net' . DS . $imgthumb;
```
将http://c.etimg.net 修改为你的URL

5. 后台登陆地址
>  http://youweb.com/sysadminlogin

# 使用
最后，如果有问题请到我的博客, https://www.fungj.com 和我联系。
