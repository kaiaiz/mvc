<?php
//判断单入口
if (!defined('WEB')) {
    echo '访问错误!';
    exit;
}
class route
{
    public static $m;//定义 static执行效率更高，指向本地,函数中使用:self::$m
    public static $f;
    public static $a;
    private function getInfo()
    {
        self::$m=isset($_REQUEST['m'])&&!empty($_REQUEST['m'])?$_REQUEST['m']:'index';
        self::$f=isset($_REQUEST['f'])&&!empty($_REQUEST['f'])?$_REQUEST['f']:'index';
        self::$a=isset($_REQUEST['a'])&&!empty($_REQUEST['a'])?$_REQUEST['a']:'init';
    }
    public function set()
    {
      //获取参数信息
        $this->getInfo();
        $murl=MODULES_PATH.self::$m;
        if (is_dir($murl)) {//判断模块下是否有此目录
            // 访问文件
            $furl=MODULES_PATH.self::$m.'/'.self::$f.'.class.php';
            if (is_file($furl)) {//判断目录下是否有此文件
                //访问文件
                include_once($furl);
                $furl=MODULES_PATH.'/'.self::$m.'/'.self::$f.'/'.self::$a;
                if (class_exists(self::$f)) {//判断是否有此类，即为文件名
                    $obj=new self::$f();
                    $method=self::$a;
                    if (method_exists($obj,$method)) {//判断是否存在此方法
                        $obj->$method();//只能调用公共方法
                    } else {
                        echo '不存在此方法<br />';
                    }
                } else {
                    echo '不存在此类<br />';
                }
            } else {
                echo '不存在此文件<br />';
            }
        } else {
            // 错误提示
            echo '不存在此目录<br />';
        }
    }
}
$route=new route();
