<?php
/*
*	 欢迎使用SEO ALL插件，如果您对插件有任何建议或者想法可以通过以下方式联系我们
*	 官方网址: http://www.v7v3.com
*	 E-mail: www@v7v3.com
*	 WeiBo: http://weibo.com/xnz233
*
#################
*
*	后台设置类文件
*
*/
Class SAadminByNaiZui{
/*
*
*	低版本php兼容方法
*
*/
	function SAadminByNaiZui(){
		$this->__construct();
	}
/*
*
*	后台设置构造方法
*
*/
	function __construct(){
		add_action( 'admin_enqueue_scripts', array($this ,'sa_script') );
		add_action( 'admin_menu', array($this ,'sa_menu') );
		
		
		add_action( 'load-post.php', array($this ,'posts_seo') );
		add_action( 'load-post-new.php', array($this ,'posts_seo') );
		
		add_action( 'wp_ajax_sa_index', array($this, 'sa_index'));
		add_action( 'wp_ajax_sa_sitemap', array($this, 'sa_sitemap'));
		add_action( 'wp_ajax_sa_push', array($this, 'sa_push'));
		add_action( 'wp_ajax_sa_robots', array($this, 'sa_robots'));
		add_action( 'wp_ajax_sa_global', array($this, 'sa_global'));
		add_action( 'wp_ajax_sa_other', array($this, 'sa_other'));
		add_action( 'wp_ajax_sa_mkrobots', array($this, 'sa_mkrobots'));
		add_action( 'wp_ajax_sa_mksitemap', array($this, 'sa_mksitemap'));
		
		add_action( 'save_post', array($this, 'savePostSeoAll'), 1, 2);
		
		$this->taxonomy_metas();
	}
/*
*
*	后台设置菜单注册函数
*
*/
	function sa_menu(){
		add_menu_page( 'SEO All', SA_NA, 'activate_plugins', 'sa_option', array($this ,'sa_option'), 'dashicons-wordpress-alt', 6 );
		//add_submenu_page( 'cst_option', 'CST系统扩展', 'CST系统扩展', 'activate_plugins', 'cst_system', array($this, 'system_page'));
		return;
	}
/*
*
*	文章编辑页面设置选项添加函数
*
*/
	function sa_add_box(){
		add_meta_box('sa_box', 'SEO All文章优化设置选项', array($this, 'sa_box'), $post_type, 'normal', 'high', array('str1','str2'));
		return;
	}
/*
*
*	添加一个meta_box（添加一个自定义域设置盒子）
*
*/
	function posts_seo(){
		add_action('add_meta_boxes',array($this, 'sa_add_box'));
		return;
	}
/*
*
*	添加taxonomy设置选项方法
*
*/
	function taxonomy_metas(){
		$data = get_option('seo_all_other', null);
		!$data['taxonomy'] ? $taxonomies = array('category','post_tag') : $taxonomies = array_merge(explode(',', $data['taxonomy']), array('category','post_tag'));
		foreach($taxonomies as $tax){
			add_action($tax . '_add_form_fields', array($this ,'sa_add_taxonomy_field'), 10, 2);
			add_action($tax . '_edit_form_fields', array($this ,'sa_edit_taxonomy_field'), 10, 2);
			add_action('edited_' . $tax, array($this ,'sa_taxonomy_metadata'), 10, 1);
			add_action('created_' . $tax,  array($this ,'sa_taxonomy_metadata'), 10, 1);
		}
		return;
	}
/*
*
*	后台设置页面方法
*
*/	
	function sa_option(){
		echo '
		<div id="seo_all" class="wrap">
		<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="javascript:;">首页SEO设置</a>
		<a class="nav-tab" href="javascript:;">StieMap生成设置</a>
		<!--<a class="nav-tab" href="javascript:;">自动内链设置</a>-->
		<a class="nav-tab" href="javascript:;">百度SiteMap实时推送设置</a>
		<a class="nav-tab" href="javascript:;">robots.txt生成</a>
		<a class="nav-tab" href="javascript:;">全局设置</a>
		<a class="nav-tab" href="javascript:;">其他杂项</a>
		</h2>
		<div id="sa_index" class="div-hidden">'.$this->opt_index().'<div id="index_m">'.$this->messg().'</div></div>
		<div id="sa_sitemap" class="div-hidden">'.$this->opt_sitemap().'<div id="sitemap_m">'.$this->messg().'</div></div>
		<!--<div id="sa_link" class="div-hidden">'.$this->opt_link().'<div id="link_m">'.$this->messg().'</div></div>-->
		<div id="sa_push" class="div-hidden">'.$this->opt_push().'<div id="push_m">'.$this->messg().'</div></div>
		<div id="sa_robots" class="div-hidden">'.$this->opt_robots().'<div id="robots_m">'.$this->messg().'</div></div>
		<div id="sa_global" class="div-hidden">'.$this->opt_global().'<div id="global_m">'.$this->messg().'</div></div>
		<div id="sa_other" class="div-hidden">'.$this->opt_other().'<div id="other_m">'.$this->messg().'</div></div>
		</div>
		';
		return;
	}
/*
*
*	首页SEO设置方法
*
*/	
	function opt_index(){
		$index = get_option( 'seo_all_index', array('title'=>null,'keyword'=>null,'description'=>null,'robots'=>null));
		$html = "<form>
		<span for='index_title'><h3>首页标题：</h3><input type='text' name='index_title' value='".$index['title']."'></span>
		<span for='index_keyword'><h3>首页关键词：</h3><input type='text' name='index_keywords' value='".$index['keywords']."'></span>
		<span for='index_description'><h3>首页描述：</h3><textarea name='index_description'>".$index['description']."</textarea></span>
		<span for='index_robots'><h3>Meta Robots：</h3><input type='text' name='index_robots' value='".$index['robots']."'></span>
		<span for='index_button'><input id='index_button' type='button' name='button' class='button button-primary' value='提交'></span>
		</form>";
		return $html;
	}
/*
*
*	网站地图设置方法
*
*/	
	function opt_sitemap(){
		$sitemap = get_option('seo_all_sitemap', array('cat'=>null,'tag'=>null,'single'=>null,'html'=>null,'auto'=>null));
		$html = "<form>
		<span for='sitemap_exclude_cat'><h3>SiteMap排除分类的ID：</h3><input type='text' name='sitemap_exclude_cat' value='".$sitemap['cat']."'></span>
		<span for='sitemap_exclude_tag'><h3>SiteMap排除标签的ID：</h3><input type='text' name='sitemap_exclude_tag' value='".$sitemap['tag']."'></span>
		<span for='sitemap_exclude_single'><h3>SiteMap排除文章的ID：</h3><input type='text' name='sitemap_exclude_single' value='".$sitemap['single']."'></span>
		<span for='sitemap_html'><h3>是否生成SiteMap HTML格式：</h3>". $this->radio_method('sitemap_html', $sitemap['html']) ."</span>
		<span for='sitemap_auto'><h3>是否自动更新SiteMap：</h3>" . $this->radio_method('sitemap_auto',$sitemap['auto']) ."</span>
		<span for='sitemap_button'><input id='sitemap_button' type='button' name='button' class='button button-primary' value='提交'> <input id='sitemap_button_mk' type='button' name='button' class='button button-primary' value='生成SiteMap'></span>
		</form>";
		return $html;
	}
/*
*
*	设置提醒模块方法
*
*/	
	function messg(){
		$html = '<div class="message"><span class="s_b"> <b class="b1"></b> <b class="b2"></b></span><div class="info"></div><span class="s_b"><b class="b2"></b> <b class="b1"></b></span><span class="s_i"> <i class="i6"></i> <i class="i5"></i> <i class="i4"></i> <i class="i3"></i> <i class="i2"></i> <i class="i1"></i></span></div>';
		return $html;
	}
/*
*
*	自动内链设置方法
*
*/	
	function opt_link(){
		$html = "<form>
		<span for='link_title'><h3>关键词：</h3><input type='text' name='link_title'></span>
		<span for='link_href'><h3>链接：</h3><input type='text' name='link_href'></span>
		<span for='link_nofollow'><h3>是否添加nofollow属性：</h3>
		<input type='radio' name='Rad' value='单选'>是
		<input type='radio' name='Rad' value='单选' checked>否
		</span>
		<span for='link_nofollow'><h3>评论中是否描文本：</h3>
		<input type='radio' name='Rd' value='单选' checked>是
		<input type='radio' name='Rd' value='单选'>否
		</span>
		<span for='link_nofollow'><h3>是否新窗口打开：</h3>
		<input type='radio' name='Ra' value='单选' checked>是
		<input type='radio' name='Ra' value='单选'>否
		</span>
		<span for='link_button'><input type='button' name='button' class='button button-primary' value='提交'></span>
		</form>";
		return $html;
	}
/*
*
*	百度SiteMap推送设置方法
*
*/	
	function opt_push(){
		$push = get_option('seo_all_baidu_push', array('key'=>null,'auto'=>null));
		$html = "<form>
		<span for='push_key'><h3>推送地址：</h3><input type='text' name='push_key' value='".$push['key']."'></span>
		<span for='push_auto'><h3>是否自动推送：</h3>" . $this->radio_method('push_auto',$push['auto']) . "</span>
		<span for='push_button'><input id='push_button' type='button' name='button' class='button button-primary' value='提交'></span>
		</form>";
		return $html;
	}
/*
*
*	robots.txt设置方法
*
*/	
	function opt_robots(){
		$robots = get_option('seo_all_robots', null);
		$html = "<from>
		<span for='robots'><textarea name='sa_robots'>".$robots."</textarea></span>
		<span for='robots_button'><input id='robots_button' type='button' name='button' class='button button-primary' value='保存'> <input id='robots_button_mk' type='button' name='button' class='button button-primary' value='生成'></span>
		</from>";
		return $html;
	}
/*
*
*	全局设置方法
*
*/	
	function opt_global(){
		$globals = get_option('seo_all_global', array('title'=>null,'nofollow'=>null,'linkgo'=>null,'imgalt'=>null,'desc'=>null,'plnofollow'=>null,'pllinkgo'=>null,'date'=>null,'archive'=>null));
		$html = "<from>
		<span><h3>title后缀：</h3><input type='text' name='global_title' value='" . $globals['title'] . "'></span>
		<span for='global_nofollow'><h3>文章外链自动nofollow：</h3>" . $this->radio_method('global_nofollow', $globals['nofollow']) . "</span>
		<span for='global_linkgo'><h3>文章外链自动转跳：</h3>" . $this->radio_method('global_linkgo', $globals['linkgo']) . "</span>
		<span for='global_imgalt'><h3>文章图片自动添加alt：</h3>" . $this->radio_method('global_imgalt', $globals['imgalt']) . "</span>
		<span for='global_desc'><h3>文章Description自动获取：</h3>" . $this->radio_method('global_desc', $globals['desc']) . "</span>
		<span for='global_plnofollow'><h3>评论外链nofollow：</h3>" . $this->radio_method('global_plnofollow', $globals['plnofollow']) . "</span>
		<span for='global_pllinkgo'><h3>评论外链转跳：</h3>" . $this->radio_method('global_pllinkgo', $globals['pllinkgo']) . "</span>
		<span for='global_date'><h3>是否屏蔽时间存档页收录：</h3>" . $this->radio_method('global_date', $globals['date']) . "</span>
		<span for='global_archive'><h3>是否屏蔽作者存档页收录：</h3>" . $this->radio_method('global_archive', $globals['archive']) . "</span>
		<span for='global_button'><input id='global_button' type='button' name='button' class='button button-primary' value='提交'></span>
		</from>";
		return $html;
	}
/*
*
*	杂项设置方法
*
*/	
	function opt_other(){
		$other = get_option('seo_all_other', array('taxonomy'=>null,'category'=>null));
		$html = "<from>
		<span><h3>可seo的taxonomy：</h3><input type='text' name='other_taxonomy' value='" . $other['taxonomy'] . "'></span>
		<span for='other_category'><h3>分类链接去除category：</h3>" . $this->radio_method('other_category',$other['category']) ."</span>
		<span for='other_button'><input id='other_button' type='button' name='button' class='button button-primary' value='提交'></span>
		</from>";
		return $html;
	}
/*
*
*	新增taxonomy时添加设置选项方法
*
*/	
	function sa_add_taxonomy_field(){
		isset($_GET['taxonomy']) ? $taxonomy = $_GET['taxonomy'] : $taxonomy = NULL;
		echo '<div class="form-field">
		<input type="hidden" name="act" value="add"/>
		<input type="hidden" name="taxonomy" value="'.$taxonomy.'"/>
		<label for="sa_taxonomy_title" >标题</label><input type="text" name="sa_taxonomy_title"/>
		<label for="sa_taxonomy_keywords" >关键词</label><input type="text" name="sa_taxonomy_keywords"/>
		<label for="sa_taxonomy_robots" >Meta Robots</label><input type="text" name="sa_taxonomy_robots"/>
		</div>';
		return;
	}
/*
*
*	编辑taxonomy时新增一个设置选项
*
*/	
	function sa_edit_taxonomy_field($tag){
		isset($_GET['taxonomy']) ? $taxonomy = $_GET['taxonomy'] : $taxonomy = NULL;
		$data = get_option('sa_' . $tag->taxonomy . $tag->term_id, array('title'=>null,'keywords'=>null,'robots'=>null));
		echo '<input type="hidden" name="act" value="edit"/><input type="hidden" name="taxonomy" value="'.$taxonomy.'"/>
		<tr><th>标题</th><td><input type="text" size="80" value="'. $data['title'] .'" name="sa_taxonomy_title"/></td></tr>
		<tr><th>关键词</th><td><input type="text" size="80" value="'. $data['keywords'] .'" name="sa_taxonomy_keywords"/></td></tr>
		<tr><th>Meta Robots</th><td><input type="text" size="80" value="'. $data['robots'] .'" name="sa_taxonomy_robots"/></td></tr>';
		return;
	}
/*
*
*	taxonomy设置值保存方法
*
*/	
	function sa_taxonomy_metadata($term_id){
		$data = array();
		if( isset($_POST['act']) && isset($_POST['taxonomy']) ){
			if(!current_user_can('manage_categories')){
				return;
			}
			isset($_POST['sa_taxonomy_title']) ? $data['title'] = $_POST['sa_taxonomy_title'] : null;
			isset($_POST['sa_taxonomy_keywords']) ? $data['keywords'] = $_POST['sa_taxonomy_keywords'] : null;
			isset($_POST['sa_taxonomy_robots']) ? $data['robots'] = $_POST['sa_taxonomy_robots'] : null;
			update_option('sa_' . $_POST['taxonomy'] . $term_id, $data);
			return;
		}
	}
/*
*
*	文章seo模块设置方法
*
*/	
	function sa_box($post,$boxargs){
		$data = get_post_meta( $post->ID, 'post_seo_data', true);
		if( !$data ) $data = array('keywords'=>null,'description'=>null,'robots'=>null);
		echo "<div id='seo_post'>
		<span><label for='v7v3comwp'><b>页面关键词</b></label><input name='seo_post_keywords' style='height:32px;width:100%;' type='text' value='". $data['keywords'] ."'/></span>
		<span><label for='v7v3comwp'><b>页面描述</b></label><textarea name='seo_post_description' style='height:200px;width:100%;'>". $data['description'] ."</textarea></span>
		<span><label for='v7v3comdx'><b>Meta Robots</b></label><input name='seo_post_robots' style='height:32px;width:100%;' type='text' value='". $data['robots'] ."'/></span>
		</div>";
		return;
	}
/*
*
*	ajax数据保存方法
*
*/	
	function sa_index(){
		function rz($str){
			$zz = "/^\s+$/";
			if( isset($str) && !preg_match($zz,$str) ){
				return true;
			}else return false;
		}
		$data = array();
		rz($_POST['index_title']) ? $data['title'] = $_POST['index_title'] : null;
		rz($_POST['index_keywords']) ? $data['keywords'] = $_POST['index_keywords'] : null;
		rz($_POST['index_description']) ? $data['description'] = $_POST['index_description'] : null;
		rz($_POST['index_robots']) ? $data['robots'] = $_POST['index_robots'] : null;
		if( !$data['title'] && !$data['keywords'] && !$data['description'] && !$data['robots'] ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>404)));
		}
		if( update_option('seo_all_index',$data) ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('up'=>500)));
		}
		die;
	}
/*
*
*	ajax数据保存方法
*
*/	
	function sa_sitemap(){
		function rz($str){
			$zz = "/^\s+$/";
			if( isset($str) && !preg_match($zz,$str) ){
				return true;
			}else return false;
		}
		$data = array();
		rz($_POST['sitemap_exclude_cat']) ? $data['cat'] = $_POST['sitemap_exclude_cat'] : null;
		rz($_POST['sitemap_exclude_tag']) ? $data['tag']= $_POST['sitemap_exclude_tag'] :null;
		rz($_POST['sitemap_exclude_single']) ? $data['single'] = $_POST['sitemap_exclude_single'] : null;
		rz($_POST['sitemap_html']) ? $data['html'] = $_POST['sitemap_html'] : null;
		rz($_POST['sitemap_auto']) ? $data['auto'] = $_POST['sitemap_auto'] : null;
		if( update_option('seo_all_sitemap',$data) ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('up'=>500)));
		}
		die;
	}
/*
*
*	ajax数据保存方法
*
*/	
	function sa_global(){
		function rz($str){
			$zz = "/^\s+$/";
			if( isset($str) && !preg_match($zz,$str) ){
				return true;
			}else return false;
		}
		$data = array();
		rz($_POST['global_title']) ? $data['title'] = $_POST['global_title'] : null;
		rz($_POST['global_nofollow']) ? $data['nofollow'] = $_POST['global_nofollow'] : null;
		rz($_POST['global_linkgo']) ? $data['linkgo'] = $_POST['global_linkgo'] : null;
		rz($_POST['global_imgalt']) ? $data['imgalt'] = $_POST['global_imgalt'] : null;
		rz($_POST['global_desc']) ? $data['desc'] = $_POST['global_desc'] : null;
		rz($_POST['global_plnofollow']) ? $data['plnofollow'] = $_POST['global_plnofollow'] : null;
		rz($_POST['global_pllinkgo']) ? $data['pllinkgo'] = $_POST['global_pllinkgo'] : null;
		rz($_POST['global_date']) ? $data['date'] = $_POST['global_date'] : null;
		rz($_POST['global_archive']) ? $data['archive'] = $_POST['global_archive'] : null;
		if( update_option('seo_all_global',$data) ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('up'=>500)));
		}
		die();
	}
/*
*
*	ajax数据保存方法
*
*/	
	function sa_push(){
		function rz($str){
			$zz = "/^\s+$/";
			if( isset($str) && !preg_match($zz,$str) ){
				return true;
			}else return false;
		}
		$data = array();
		rz($_POST['push_key']) ? $data['key'] = $_POST['push_key'] : null;
		rz($_POST['push_auto']) ? $data['auto'] = $_POST['push_auto'] : null;
		if( update_option('seo_all_baidu_push',$data) ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('up'=>500)));
		}
		die;
	}
/*
*
*	ajax数据保存方法
*
*/	
	function sa_other(){
		function rz($str){
			$zz = "/^\s+$/";
			if( isset($str) && !preg_match($zz,$str) ){
				return true;
			}else return false;
		}
		$data = array();
		rz($_POST['other_taxonomy']) ? $data['taxonomy'] = $_POST['other_taxonomy'] : null;
		rz($_POST['other_category']) ? $data['category'] = $_POST['other_category'] : null;
		if( update_option('seo_all_other',$data) ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('up'=>500)));
		}
		die;
	}
/*
*
*	ajax数据保存方法
*
*/	
	function sa_robots(){
		function rz($str){
			$zz = "/^\s+$/";
			if( isset($str) && !preg_match($zz,$str) ){
				return true;
			}else return false;
		}
		rz($_POST['sa_robots']) ? $robots = $_POST['sa_robots'] : $robots = false;
		if( update_option('seo_all_robots',$robots) ){
			header('Content-type:text/json');
			die(json_encode(array('up'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('up'=>500)));
		}
		die;
	}
/*
*
*	ajax robots.txt生成方法
*
*/	
	function sa_mkrobots(){
		include SA_LIB . 'RobotsByNaiZui.Class.php';
		$r = New saRobotsByNaiZui();
		if( $r->mkfile() !== false ){
			header('Content-type:text/json');
			die(json_encode(array('mk'=>200)));
		}else{
			header('Content-type:text/json');
			die(json_encode(array('mk'=>500)));
		}
		die;
	}
/*
*
*	ajax网站地图生成方法
*
*/	
	function sa_mksitemap(){
		header('Content-type:text/json');
		include SA_LIB . 'SitemapByNaiZui.Class.php';
		$r = New saSitemapByNaiZui();
		$pass = $r->mkfile();
		switch ($pass){
			case 1:
			case 5:
				die(json_encode(array('mk'=>200)));
			case 2:
				die(json_encode(array('mk'=>300)));
			case 3:
				die(json_encode(array('mk'=>400)));
			case 4:
			case 6:
				die(json_encode(array('mk'=>500)));
			
		}
		die;
	}
/*
*
*	seo all后台设置单选生成方法
*
*/	
	function radio_method($name,$value=''){
		if( $value != 'yes' ){
			$ret = "<input type='radio' name='{$name}' value='yes'>是
			<input type='radio' name='{$name}' value='no' checked>否";
		}else{
			$ret = "<input type='radio' name='{$name}' value='yes' checked>是
			<input type='radio' name='{$name}' value='no'>否";
		}
		return $ret;
	}
/*
*
*	文章SEO数据保存方法
*
*/	
	function savePostSeoAll($post_id, $post){
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
		$data = array();
		isset($_POST['seo_post_keywords']) ? $data['keywords'] = $_POST['seo_post_keywords'] : null;
		isset($_POST['seo_post_description']) ? $data['description'] = $_POST['seo_post_description'] : null;
		isset($_POST['seo_post_robots']) ? $data['robots'] = $_POST['seo_post_robots'] : null;
		if( $data ) update_post_meta( $post_id, 'post_seo_data', $data);
		return;
	}
/*
*
*	后台css、javascript载入方法
*
*/	
	function sa_script(){
		wp_register_script( 'jq', includes_url( '/js/jquery/jquery.js'), array('jquery'), '' );
		wp_enqueue_script( 'jq' );
		if( isset($_GET['page']) && $_GET['page'] == 'sa_option' ){
			wp_register_script( 'seo_all', SA_UI . 'js/seo_all.js', array('jquery'), '' );
			wp_enqueue_script( 'seo_all' );
		}
		wp_register_style( 'cst_css', SA_UI . 'css/seo_all.css',  array(), '', 'all' );
		wp_enqueue_style( 'cst_css' );
		return;
	}
	
}