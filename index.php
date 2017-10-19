<?php
// 单入口文件
// 定义一个常量，引入其他页面，判断其他页面是否存在
// include('LIBS_PATH'.'/')
// 物理文件路径:PATH,服务器路径：URL
// 1.定义文件路径,定义常量。
// $_SERVER['DOCUMENT_ROOT'],ROOT_PATH,服务器根目录
// APP_PATH:web应用根目录
// LIB_PATH:libs文件路径
// MOUDLES_PATH:模块路径
// TPL_PATH:模板路径
// 2.服务器路径
// 协议：
// 主机地址：HOST
// 项目路径:APP_URL
// 服务器的项目路径:HTTP_URL
// css路径，js路径，img路径
// mvc(架构):control:概念,view(html+css),moudual
// 路由是实现控制器的一种方式,地址栏也有很多实现方式,地址栏是其中一种方式。控制器--路由--地址栏。路由实现调度方式。
header('content-Type:text/html;charset:utf8');
// 物理文件位置
// var_dump($_SERVER);
define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT']);//服务器根目录
$appFull=$_SERVER['SCRIPT_FILENAME'];
$pos=strrpos($appFull,'/');
define('APP_PATH',substr($appFull,0,$pos).'/');//应用根目录
define('LIB_PATH',APP_PATH.'libs/');
define('MODULES_PATH',APP_PATH.'modules/');
define('TPL_PATH',APP_PATH.'template/');
define('SMARTY_PATH',LIB_PATH.'smarty/');
//服务器路径
$protocal=$_SERVER['SERVER_PROTOCOL'];
$pos=strrpos($protocal,'/');
$protocal=strtolower(substr($protocal,0,$pos));
define('PROTOCAL',$protocal);
define('HOST',$_SERVER['HTTP_HOST']);
define('HTTP_URL',PROTOCAL.'://'.HOST.substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],'/')).'/');
define('CSS_URL',HTTP_URL.'static/css/');
define('IMG_URL',HTTP_URL.'static/img/');
define('JS_URL',HTTP_URL.'static/js/');
define('WEB','true');

include_once(LIB_PATH.'route.class.php');
// include_once(LIB_PATH.'smarty.class.php');
include_once(SMARTY_PATH.'Smarty.class.php');
// 包含模板下的文件

$route=new route();
$route->set();
//数据库配置
$config=include(APP_PATH.'config.php');
include(LIB_PATH.'mysql.class.php');
$mysql=new Mysql($config);
// $result=$mysql->getRow('select * from admin');
// $result=$mysql->table('user')->getAll();
// $result=$mysql->getCount('select * from user');
// var_dump($result);
$mysql->table('admin')->insert('a,aa');