<?php
Class AuthorSeoByNaiZui extends SeoByNaiZui{
	
	function __construct(){
		global $wp_query;
		$obj = $wp_query->get_queried_object();
		$data['title'] = '作者:' . $obj->user_nicename .'的所有文章{page}_第%page%页{/page}';
		$data['robots'] = apply_filters('author_robots', 'noindex') . ',follow';
		$this->data = $data;
		parent::__construct();
	}
	
}