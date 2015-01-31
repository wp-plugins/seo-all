<?php
Class saSitemapByNaiZui{
	
	private $data;
	
	function __construct(){
		$this->get_sitemap_data();
	}
	
	function SA_EscapeXML($string) {
		return str_replace( array ( '&', '"', "'", '<', '>'), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;'), $string);
	}
	
	function saTimeSql($mysqlDateTime) {
		list($date, $hours) = explode(' ', $mysqlDateTime);
		list($year,$month,$day) = explode('-',$date);
		list($hour,$min,$sec) = explode(':',$hours);
		return mktime(intval($hour), intval($min), intval($sec), intval($month), intval($day), intval($year));
	}
	
	function get_sitemap_data(){
		global $wpdb, $posts;
		$sitemap = get_option('seo_all_sitemap', array('cat'=>null,'tag'=>null,'single'=>null,'html'=>null,'auto'=>null));
		$sitemap['cat'] === null ? $cat = '0' : $cat = $sitemap['cat'];
		$sitemap['tag'] === null ? $tag = '0' : $tag = $sitemap['tag'];
		$sitemap['single'] === null ? $single = '0' : $single = $sitemap['single'];
		$cat_array = explode(',',$cat);
		$tag_array = explode(',',$tag);
		$single_array = explode(',',$single);
		$sql_mini = "select ID,post_modified,post_date,post_title FROM $wpdb->posts
				WHERE post_password = ''
				AND (post_type='post')
				AND post_status = 'publish'
				ORDER BY post_modified DESC
				LIMIT 0,900
			   ";
		$recentposts_mini = $wpdb->get_results($sql_mini);
		if($recentposts_mini){
			foreach ($recentposts_mini as $post){
					if( array_search($post->ID,$single_array) !== false ) continue;
					if( in_category( $cat_array, $post->ID) ) continue;
					$loc = get_permalink($post->ID);
					$loc = $this->SA_EscapeXML($loc);
					if(!$loc) continue;
					if($post->post_modified == '0000-00-00 00:00:00'){ $post_date = $post->post_date; } else { $post_date = $post->post_modified; }
					$lastmod = date("Y-m-d\TH:i:s+00:00",$this->saTimeSql($post_date));
					$changefreq = 'monthly';
					$priority = '0.6';
					$data['post'][] = array(
						'link' => $loc,
						'title' => $post->post_title,
						'time' => $lastmod,
						'changefreq' => $changefreq,
						'priority' => $priority
					);
			}
			$category_ids = get_all_category_ids();
				foreach($category_ids as $sa_cat_id){
					if( array_search($sa_cat_id,$cat_array) !== false ) continue;
					$loc = get_category_link($sa_cat_id);
					$loc = $this->SA_EscapeXML($loc);
					if(!$loc) continue;
					$lastmod = date("Y-m-d\TH:i:s+00:00",current_time('timestamp', '1'));
					$changefreq = 'Weekly';
					$priority = '0.3';
					$data['cat'][] = array(
						'link' => $loc,
						'title' => get_the_category_by_ID($sa_cat_id),
						'time' => $lastmod,
						'changefreq' => $changefreq,
						'priority' => $priority
					);
			}
			$all_the_tags = get_tags();
			if($all_the_tags){
				foreach($all_the_tags as $this_tag) {
					$tag_id = $this_tag->term_id;
					if( array_search($tag_id,$tag_array) !== false ) continue;
					$loc = get_tag_link($tag_id);
					$loc = $this->SA_EscapeXML($loc);
					if(!$loc){ continue; }
					$lastmod = date("Y-m-d\TH:i:s+00:00",current_time('timestamp', '1'));
					$changefreq = 'Weekly';
					$priority = '0.3';
					$data['tag'][] = array(
						'link' => $loc,
						'title' => $this_tag->name,
						'time' => $lastmod,
						'changefreq' => $changefreq,
						'priority' => $priority
					);
				}
			}
		$this->data = $data;
		return;
		}
	}
	
	function xml_annotate() {
		$sa_author = 'NaiZui';
		$sa_authorurl = 'http://www.v7v3.com/';
		$sa_pluginversion = '1.0.0';
		$sa_pluginurl = 'http://www.v7v3.com/';
		$blogtime = current_time('mysql'); 
		list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $blogtime );
		$xml_author_annotate = '<!-- SEO-All-version="'.$sa_pluginversion.'" plugin-author="'.$sa_author.'" plugin-url="'.$sa_pluginurl.'" author-url="'.$sa_authorurl.'" --><!-- generated-on="'."$today_year-$today_month-$today_day $hour:$minute:$second".'" -->';
		return $xml_author_annotate;
	}
	
	function xml_sitemap(){
		$blog_url = home_url();
		$blogtime = current_time('timestamp', '1');
		$blog_time = date("Y-m-d\TH:i:s+00:00",$blogtime);
		$xml_header = '<?xml version="1.0" encoding="UTF-8"?>'.$this->xml_annotate().'<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$xml_home = "<url><loc>$blog_url</loc><lastmod>$blog_time</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>";
		$xml_end = '</urlset>';
		$xml_data = $this->data;
		$xmlContent = '';
		foreach( $xml_data['post'] as $postData ){
			$xmlContent .= '<url><loc>'.$postData['link'].'</loc><lastmod>'.$postData['time'].'</lastmod><changefreq>'.$postData['changefreq'].'</changefreq><priority>'.$postData['priority'].'</priority></url>';
		};
		foreach( $xml_data['cat'] as $postData ){
			$xmlContent .= '<url><loc>'.$postData['link'].'</loc><lastmod>'.$postData['time'].'</lastmod><changefreq>'.$postData['changefreq'].'</changefreq><priority>'.$postData['priority'].'</priority></url>';
		};
		foreach( $xml_data['tag'] as $postData ){
			$xmlContent .= '<url><loc>'.$postData['link'].'</loc><lastmod>'.$postData['time'].'</lastmod><changefreq>'.$postData['changefreq'].'</changefreq><priority>'.$postData['priority'].'</priority></url>';
		};
		return $xml_header.$xml_home.$xmlContent.$xml_end;
	}
	
	function html_stiemap(){
		$html_data = $this->data;
		foreach( $html_data['post'] as $postData ){
			$postHTML .= '<li><a href="'.$postData['link'].'" title="'.$postData['title'].'" target="_blank">'.$postData['title'].'</a></li>';
		};
		foreach( $html_data['cat'] as $postData ){
			$catHTML.= '<li><a href="'.$postData['link'].'" title="'.$postData['title'].'" target="_blank">'.$postData['title'].'</a></li>';
		};
		foreach( $html_data['tag'] as $postData ){
			$tagHTML .= '<a href="'.$postData['link'].'" title="'.$postData['title'].'" target="_blank">'.$postData['title'].'</a>';
		};
		return array(
			'post' => $postHTML,
			'cat' => $catHTML,
			'tag' => '<h3>Tag Cloud</h3>'.$tagHTML
		);
	}
	
	function mkfile(){
		$sitemap = get_option('seo_all_sitemap', array('cat'=>null,'tag'=>null,'single'=>null,'html'=>null,'auto'=>null));
		$mkxml = file_put_contents(SA_WEB_RT . 'sitemap.xml', $this->xml_sitemap());
		if( $sitemap['html'] == 'yes' ){
			$blogtime = current_time('mysql'); 
			list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $blogtime );
			$blog_title = 'SiteMap';
			$blog_name = get_bloginfo('name');
			$html = file_get_contents(SA_RT . 'sitemap.html');
			$blog_home = get_bloginfo('url');
			$sitemap_url = get_bloginfo('url').'/sitemap.html';
			$xml_url = get_bloginfo('url').'/'.'sitemap.xml';
			$footnote = 'HomePage';
			$recentpost = 'RecentPost';
			$updated_time = "$today_year-$today_month-$today_day $hour:$minute:$second";
			$data = $this->html_stiemap();
			$html = str_replace("%blog_title%",$blog_title,$html);
			$html = str_replace("%blog_name%",$blog_name,$html);
			$html = str_replace("%blog_home%",$blog_home,$html);
			$html = str_replace("%sitemap_url%",$sitemap_url,$html);
			$html = str_replace("%blog_sitemap%",$xml_url,$html);
			$html = str_replace("%footnote%",$footnote,$html);
			$html = str_replace("%RecentPost%",$recentpost,$html);
			$html = str_replace("%updated_time%",$updated_time,$html);
			$html = str_replace("%contents%",$data['post'],$html);
			$html = str_replace("%sa_category_contents%",$data['cat'],$html);
			$html = str_replace("%sa_tag_contents%",$data['tag'],$html);
			$mkhtml = file_put_contents(SA_WEB_RT . 'sitemap.html', $html);
			if( $mkhtml !== false && $mkxml !== false ) return 1;
			else if(  $mkhtml !== false && $mkxml === false ) return 2;
			else if(  $mkhtml === false && $mkxml !== false ) return 3;
			else return 4;
		}else{
			if( $mkxml !== false ) return 5;
			else return 6;
		}
	}
	
}