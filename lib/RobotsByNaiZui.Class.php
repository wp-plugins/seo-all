<?php
Class saRobotsByNaiZui{
	private $text;
	
	function __construct(){
		$this->robots = get_option('seo_all_robots', null);
	}
	
	function mkfile(){
		return file_put_contents(SA_WEB_RT . 'robots.txt', $this->robots);
	}
	
}