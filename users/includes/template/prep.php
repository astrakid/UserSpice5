<?php
$db = DB::getInstance();
$settings = $db->query("SELECT * FROM settings")->first();

if(file_exists($abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/header.php')){
require_once  $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/header.php';
}else{
  //assume template has been deleted
  if(file_exists($abs_us_root . $us_url_root . 'usersc/templates/standard/header.php')){
    $db->update('settings',1,['template'=>'standard']);
    $settings->template = "standard";
    require_once $abs_us_root . $us_url_root . 'usersc/templates/standard/header.php';
    err("Template missing. Falling back to Standard Template");
  }else{
    // die("Looking for alt");
    $dirs = glob($abs_us_root . $us_url_root . 'usersc/templates/*', GLOB_ONLYDIR);
    $found = 0;
    foreach ($dirs as $d) {
      if($found == 0){
      if(file_exists($d.'/header.php')){
        $template = str_replace($abs_us_root . $us_url_root . 'usersc/templates/', "", $d);
        $db->update('settings',1,['template'=>$template]);
        $settings->template = $template;
        require_once $abs_us_root . $us_url_root . 'usersc/templates/'.$template.'/header.php';
        $template = ucfirst($template);
        err("Template missing. Falling back to $template Template");
        $found = 1;
      }
    }
    }
    if($found == 0){
      die("You do not appear to have a valid template installed");
    }
  }
}
require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/navigation.php'; //custom template nav
require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/container_open.php'; //custom template container
