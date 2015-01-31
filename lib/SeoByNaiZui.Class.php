<?php
Class SeoByNaiZui{
	
	protected $data;
	private $nopagedData;
	private $pagedData;
	
	function __construct(){
		$data = $this->data;
		$this->title();
		if( $data['keywords'] )
		$this->keywords();
		if( $data['description'] )
		$this->description();
		if( $data['robots'] )
		$this->robots();
	}
	
	protected function paged_seo($str){
		global $paged;
		$zz = "/{page}(.+?){\/page}/i";
		if( $paged > 1 ){
			if( @preg_match($zz,$str,$arr) ){
				$Q = str_replace($arr[0], '', $str) . $arr[1];
			}else{
				$Q = $str;
			}
			return str_replace('%page%', $paged, $Q);
		}else{
			if( @preg_match($zz,$str,$arr) ){
				$Q = str_replace($arr[0], '', $str);
			}else{
				$Q = $str;
			}
			return str_replace('%page%', '', $Q);
		}
	}
	
	protected function title(){
		$data = $this->data;
		if( $this->paged_seo($data['title']) )
		print "<title>" . $this->paged_seo($data['title']) . apply_filters('seo_all_title', "</title>") . "\n";
		return;
	}
	
	protected final function keywords(){
		$data = $this->data;
		if(  $this->paged_seo($data['keywords']) )
		print "<meta name=\"keywords\" content='" . $this->paged_seo($data['keywords']) . "'/>\n";
		return;
	}
	
	protected final function description(){
		$data = $this->data;
		if( $this->paged_seo($data['description']) )
		print "<meta name=\"Description\" content='" . $this->paged_seo($data['description']) . "'/>\n";
		return;
	}
	
	protected final function robots(){
		$data = $this->data;
		if( $this->paged_seo($data['robots']) )
		print "<meta name=\"robots\" content='" . $this->paged_seo($data['robots']) . "'/>\n";
		return;
	}
	
}