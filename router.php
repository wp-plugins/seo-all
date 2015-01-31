<?php
/*
*	 欢迎使用SEO ALL插件，如果您对插件有任何建议或者想法可以通过以下方式联系我们
*	 官方网址: http://www.v7v3.com
*	 E-mail: www@v7v3.com
*	 WeiBo: http://weibo.com/xnz233
*
#################
*
*	SEO All路由文件，引入设置模块实例化设置对象
*	实例化后return结束代码块执行，降低插件资源消耗
*
*/
require SA_RT . 'lib/AdminPageByNaiZui.Class.php';
if ( is_admin() ){
	$SAadmin = New SAadminByNaiZui();
	return;
}