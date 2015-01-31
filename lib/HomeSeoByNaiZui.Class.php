<?php
Class HomeSeoByNaiZui extends SeoByNaiZui{
	
	function __construct(){
		$data = get_option( 'seo_all_index', array('title'=>null,'keywords'=>null,'description'=>null,'robots'=>null));
		if( !$data['title'] ){
			$data['title'] = get_bloginfo('name') . '{page}_第%page%页{/page}';
		}
		$this->data = $data;
		parent::__construct();
	}
	
}