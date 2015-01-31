<?php
Class TaxonomySeoByNaiZui extends SeoByNaiZui{
	
	function __construct(){
		global $wp_query;
		$tax = $wp_query->get_queried_object();
		$Nz = get_option('sa_' . $tax->taxonomy . $tax->term_taxonomy_id, array('title'=>null,'keywrods'=>null,'robots'=>null));
		$Nz['description'] = term_description($tax->term_taxonomy_id, $tax->taxonomy);
		if( !$NZ['title'] ){
			$Nz['title'] = $tax->name . '{page}_第%page%页{/page}';
		}
		$this->data = $Nz;
		parent::__construct();
	}
	
}