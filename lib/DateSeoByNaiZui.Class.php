<?php
Class DateSeoByNaiZui extends SeoByNaiZui{
	
	function __construct(){
		$data['title'] = '时间存档页:' . get_bloginfo('name') . '{page}_第%page%页{/page}';
		$data['robots'] = apply_filters('data_robots', 'noindex') . ',follow';
		$this->data = $data;
		parent::__construct();
	}
	
}