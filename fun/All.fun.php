<?php
function seo_all_go_url(){  
	global $pagenow;
	if(is_home&&$pagenow=='index.php'){
	$location=$_GET['r'];
		if($location!=""){
			wp_redirect(esc_url_raw($location),302);
			exit;
		}
	}
}

function seo_all_fun(){
	include SA_LIB . 'SeoByNaiZui.Class.php';
	if( is_home() ){
		include SA_LIB . 'HomeSeoByNaiZui.Class.php';
		$Object = New HomeSeoByNaiZui();
		return;
	}
	else if( is_tax() || is_tag() || is_category() ){
		include SA_LIB . 'TaxonomySeoByNaiZui.Class.php';
		$Object = New TaxonomySeoByNaiZui();
		return;
	}
	else if( is_singular() ){
		include SA_LIB . 'SingularSeoByNaiZui.Class.php';
		$Object = New SingularSeoByNaiZui();
		return;
	}
	else if( is_date() ){
		include SA_LIB . 'DateSeoByNaiZui.Class.php';
		$Object = New DateSeoByNaiZui();
		return;
	}
	else if( is_author() ){
		include SA_LIB . 'AuthorSeoByNaiZui.Class.php';
		$Object = New AuthorSeoByNaiZui();
		return;
	}
	return;
}

function seo_all_title_filter(){
	global $globals;
	$filter = $globals['title'] . '</title>';
	return $filter;
}

function seo_all_robots_index(){
	return 'index';
}

function seo_all_link_nofollow( $content ) {
	global $globals;
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
	$zz = "/href=['\"](.+)['\"]/siU";
    if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)){
		
        if( !empty($matches) ) {
            $srcUrl = get_option('siteurl');
            for ($i=0; $i < count($matches); $i++)
            {
   
                $tag = $matches[$i][0];
                $tag2 = $matches[$i][0];
                $url = $matches[$i][0];
   
                $noFollow = '';
                $pattern = '/target\s*=\s*"\s*_blank\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if( count($match) < 1 )
                    $noFollow .= ' target="_blank" ';
   
                $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if( count($match) < 1 )
                    $noFollow .= ' rel="nofollow" ';
   
                $pos = strpos($url,$srcUrl);
                if ($pos === false) {
                    $tag = rtrim ($tag,'>');
                    $tag .= $noFollow.'>';
					if( $globals['linkgo'] == 'yes' || $globals['pllinkgo'] == 'yes' )
					$tag = preg_replace($zz, "href='" . get_option('siteurl') . "/?r=$1'", $tag);
                    $content = str_replace($tag2,$tag,$content);
                }
            }
        }
    }   
   
    $content = str_replace(']]>', ']]>', $content);
    return $content;
}

function seo_all_no_category_base_refresh_rules() {
    global $wp_rewrite;
    $wp_rewrite ->flush_rules();
}

add_action('init', 'seo_all_no_category_base_permastruct');
function seo_all_no_category_base_permastruct() {
    global $wp_rewrite, $wp_version;
    if (version_compare($wp_version, '3.4', '<')) {
        // For pre-3.4 support
        $wp_rewrite -> extra_permastructs['category'][0] = '%category%';
    } else {
        $wp_rewrite -> extra_permastructs['category']['struct'] = '%category%';
    }
}
// Add our custom category rewrite rules
add_filter('category_rewrite_rules', 'seo_all_no_category_base_rewrite_rules');
function seo_all_no_category_base_rewrite_rules($category_rewrite) {
    //var_dump($category_rewrite); // For Debugging
    $category_rewrite = array();
    $categories = get_categories(array('hide_empty' => false));
    foreach ($categories as $category) {
        $category_nicename = $category -> slug;
        if ($category -> parent == $category -> cat_ID)// recursive recursion
            $category -> parent = 0;
        elseif ($category -> parent != 0)
            $category_nicename = get_category_parents($category -> parent, false, '/', true) . $category_nicename;
        $category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
        $category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
        $category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
    }
    // Redirect support from Old Category Base
    global $wp_rewrite;
    $old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
    $old_category_base = trim($old_category_base, '/');
    $category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
    
    //var_dump($category_rewrite); // For Debugging
    return $category_rewrite;
}
    
// Add 'category_redirect' query variable
add_filter('query_vars', 'seo_all_no_category_base_query_vars');
function seo_all_no_category_base_query_vars($public_query_vars) {
    $public_query_vars[] = 'category_redirect';
    return $public_query_vars;
}
    
// Redirect if 'category_redirect' is set
add_filter('request', 'seo_all_no_category_base_request');
function seo_all_no_category_base_request($query_vars) {
    //print_r($query_vars); // For Debugging
    if (isset($query_vars['category_redirect'])) {
        $catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
        status_header(301);
        header("Location: $catlink");
        exit();
    }
    return $query_vars;
}

function seo_all_auto_sitemap(){
	include SA_LIB . 'SitemapByNaiZui.Class.php';
	$r = New saSitemapByNaiZui();
	$pass = $r->mkfile();
}

function seo_all_PushBaiDu($id=null,$p=null){
	$data = get_option('seo_all_baidu_push', array('key'=>null,'auto'=>null));
	if( !$data['key'] ){
		return;
	}
	$key = $data['key'];
	if( $id != null && $p != null ){
		$post_id = $id;
		$post = $p;
	}else{
		global $post_id,$post;
	}
	$N = get_post_meta( $post_id, 'Baidu_Push', true);
	if( $N ){
		return;
	}
    $PushUrl = get_permalink($post_id);
    $PushDate = $post->post_data;
    $PushXml = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset>
        <url>
            <loc><![CDATA['.$PushUrl.']]></loc>
            <lastmod>'.$PushDate.'</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    </urlset>';
    $wp_http_obj = new WP_Http();
    if ( $wp_http_obj->post($key, array('body' => $PushXml, 'headers' => array('Content-Type' => 'text/xml'))) ){
		update_post_meta( $post_id, 'Baidu_Push', 1);
		return;
	}
}