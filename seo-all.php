<?php
/*
Plugin Name: SEO All
Plugin URI: http://www.v7v3.com
Description: 一款真正意义上的站内全面SEO插件，比以往的任何SEO插件都要强大。
Version: 1.0
Author: NaiZui
Author URI: http://www.v7v3.com
*/

/*
*	 欢迎使用SEO ALL插件，如果您对插件有任何建议或者想法可以通过以下方式联系我们
*	 官方网址: http://www.v7v3.com
*	 E-mail: www@v7v3.com
*	 WeiBo: http://weibo.com/xnz233
*
#################
*
*	SEO All插件常量定义
*
*/
define('SA_NA','SEO All');
define('SA_RT',plugin_dir_path(__FILE__));
define('SA_UI',plugins_url('/',__FILE__));
define('SA_LIB',SA_RT.'lib/');
define('SA_FUN',SA_RT.'fun/');
define('SA_WEB_RT',ABSPATH);

/*
*
*	SEO All后台设置载入路由
*	引入路由后return结束代码块执行，降低插件消耗
*
*/
if( is_admin() ){
	require SA_RT.'router.php';
	return;
}
/*
*
*	SEO All前端功能载入路由
*	引入路由后return结束代码块执行，降低插件消耗
*
*/
if( !is_admin() ){
	require SA_FUN.'ModeByNaiZui.Fun.php';
	return;
}
