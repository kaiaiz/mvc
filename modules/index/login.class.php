<?php
class login{
  public function init(){
    // $smarty=new smarty();
    // $smarty->setTemplatePATH('template');
    // $smarty->setCompilePATH('compile');
    // $smarty->setCache('cache');
    // $smarty->assign('name','lisi');
    // $smarty->display('index/index.html');
    //smarty
    $smarty=new Smarty();
    $smarty->getTemplateDir();
    $smarty->getCompileDir();
    $smarty->getCacheDir();
    $smarty->assign('name','lisi');
    $smarty->display(TPL_PATH.'index/index.html');
  }
}