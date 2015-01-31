<?php
$globals = get_option('seo_all_global', array('title'=>null,'nofollow'=>null,'linkgo'=>null,'imgalt'=>null,'desc'=>null,'plnofollow'=>null,'pllinkgo'=>null,'date'=>null,'archive'=>null));
$other = get_option('seo_all_other', null);
$push = get_option('seo_all_baidu_push', array('key'=>null,'auto'=>null));
$sitemap = get_option('seo_all_sitemap', array('cat'=>null,'tag'=>null,'single'=>null,'html'=>null,'auto'=>null));
require SA_FUN . 'All.Fun.php';
if( $globals['title'] )
add_filter( 'seo_all_title', 'seo_all_title_filter');
if( $globals['date'] == 'no' )
add_filter( 'data_robots', 'seo_all_robots_index');
if( $globals['archive'] == 'no' )
add_filter( 'author_robots', 'seo_all_robots_index');
if( $globals['nofollow'] == 'yes' )
add_filter('the_content', 'seo_all_link_nofollow');
if( $globals['linkgo'] == 'yes' || $globals['pllinkgo'] == 'yes' )
add_action('init','seo_all_go_url');
if( $globals['plnofollow'] == 'yes' ){
	add_filter('get_comment_author_link', 'seo_all_link_nofollow', 5);
	add_filter('comment_text', 'seo_all_link_nofollow', 99);
}
if( $other['category'] == 'yes' ){
	add_action( 'load-themes.php',  'seo_all_no_category_base_refresh_rules');
	add_action('created_category', 'seo_all_no_category_base_refresh_rules');
	add_action('edited_category', 'seo_all_no_category_base_refresh_rules');
	add_action('delete_category', 'seo_all_no_category_base_refresh_rules');
}
if( $sitemap['auto'] == 'yes' ){
	add_action( 'publish_post', 'seo_all_auto_sitemap');
}
if( $push['key'] && $push['auto'] ){
	add_action('publish_post', 'seo_all_PushBaiDu');
}