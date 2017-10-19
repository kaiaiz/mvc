<?php
// 判断单入口
if(!define(WEB)){
    echo '访问错误!';
    exit;
}
class smarty
{
    private $templatePATH;
    private $compilePATH;
    private $cachePATH;
    private $compileArray=array();//初始化数组
    public function setTemplatePATH($template = 'template')
    {
        $temp=APP_PATH.$template;
        if (!is_dir($temp)) {
            mkdir($temp);
        }
            $this->templatePATH=$temp;
    }
    public function setCompilePATH($compile = 'compile')
    {
        $temp=APP_PATH.$compile;
        if (!is_dir($temp)) {
            mkdir($temp);
        }
            $this->compilePATH=$temp;
    }
    public function setCache($cache = 'cache')//为提高访问效率，设置缓存，将php生成的页面数据存储，当访问时判断是否
    {
        $temp=APP_PATH.$cache;
        if (!is_dir($temp)) {
            mkdir($temp);
        }
            $this->cachePATH=$temp;
    }
    public function assign($key, $value)
    {
        $this->compileArray[$key]=$value;
    }
    public function display($url)
    {
        $originalPath=$this->templatePATH.'/'.$url;
        $original=file_get_contents($originalPath);//得到原文件未编译内容
        $change=preg_replace("/\{([^\}\s]+)\}/", '<?php echo $this->compileArray["$1"]; ?>', $original);//$1匹配正则括号后的第一个字符

        $changePath=$this->compilePATH.'/'.md5($url).'php';//由于md5得到同一个名字的散列值相同，所以访问同一个页面会覆盖.实际上访问的是新建的文件
            $cacheFull=$this->cachePATH.'/'.md5($url).'php';
        if (is_file($cacheFull)) {//如果存在缓存则直接访问，如果不存在则输出编译文件并保存到缓存。
            include_once($cacheFull);
        } else {
            ob_start();//打开缓冲区输出。
            file_put_contents($changePath, $change);//写入编译后的文件
            include_once($changePath);
            $contents=ob_get_contents();
            file_put_contents($cacheFull, $contents);
        }
    }
}
