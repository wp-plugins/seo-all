<?php
Class SingularSeoByNaiZui extends SeoByNaiZui{
	
	function __construct(){
		global $post,$globals;
		$data = get_post_meta($post->ID, 'post_seo_data', true);
		$data['title'] = $post->post_title;
		if( !$data['description'] && $globals['desc'] == 'yes' )
			$data['description'] = mb_strimwidth(strip_tags(preg_replace('/\s/', '', $post->post_content)), 0, 200,"......","utf-8");
		$this->data = $data;
		parent::__construct();
	}
	
}