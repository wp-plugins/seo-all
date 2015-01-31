jQuery(document).ready(function(){
	jQuery(function($){
	function z_title(str){
		var zz = /\s/;
		str = str.replace('{<page>}','');
		if( zz.test(str) ){
			return '标题中含有空格等空白字符建议删除空白字符。';
		}
		return true;
	}
	function z_keywords(str){
		str = str.replace('{<page>}','');
		if(str.indexOf('，') != -1){
			return '分割关键词请使用英文逗号“,”而不是中文输入法下的逗号。';
		}
		return true;
	}
	function z_id(str){
		if(str.indexOf('，') != -1){
			return '使用英文逗号“,”分割id而不是中文输入法下的逗号。';
		}
		return true;
	}
	function z_description(str){
		var zz = /\s/;
		str = str.replace('{<page>}','');
		if( zz.test(str) & str.length > 220 ){
			return '页面描述中中含有空格回车等空白字符，并且长度超过220个字符，建议删除空白字符并且长度精简到220个字符以下。';
		}
		if(str.length > 220){
			return '页面长度大于220个字符，建议且长度精简到220个字符以下。';
		}
		if( zz.test(str) ){
			return '页面描述中中含有空格回车等空白字符，建议删除空白字符。';
		}
		return true;
	}
	function z_robots(str){
		str = str.replace('{<pg>}','');
		var len = str.split(','),robots=['noindex','nofollow','follow','index','noarchive'],zz = /\s/;
		if( zz.test(str) ){
			return 'meta robots标签内不允许包含空格。';
		}
		if( len.length > 3 ){
			return '错误的Meta Robots。';
		}
		if(str.indexOf('，') != -1){
			return 'meta robots标签内容请使用英文逗号分隔。';
		}
		return true;
	}
	var hidden=$('#seo_all .div-hidden'),tabs=$('#seo_all .nav-tab'),im=$("index_m"),sm=$("sitemap_m"),lm=$("link_m"),pm=$("push_m"),rm=$("robots_m");
	var kb = /^[ \f\n\r\t\v]+$/;
	hidden.hide();
	$('#seo_all div[id$="_m"]>div.message').hide();
	hidden.first().show();
	tabs.first().addClass('nav-tab-active');
	tabs.on('click',function(){
		hidden.hide();
		tabs.removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$(hidden[$(this).index()]).show();
	});
	$('input[name="index_title"]').keyup(function(){
			if( kb.test($('input[name="index_title"]').val()) ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text('首页标题为空！');
			}
			else if( z_title($('input[name="index_title"]').val()) != true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text(z_title($('input[name="index_title"]').val()));
			}
			else if( z_title($('input[name="index_title"]').val()) == true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').hide();
			}
		});
		$('input[name="index_keywords"]').keyup(function(){
			if( kb.test($('input[name="index_keywords"]').val()) ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text('首页关键词为空！');
			}
			else if( z_keywords($('input[name="index_keywords"]').val()) != true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text(z_keywords($('input[name="index_keywords"]').val()));
			}
			else if( z_keywords($('input[name="index_keywords"]').val()) == true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').hide();
			}
		});
		$('textarea[name="index_description"]').keyup(function(){
			if( kb.test($('textarea[name="index_description"]').val()) ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text('首页描述为空！');
			}
			else if( z_description($('textarea[name="index_description"]').val()) != true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text(z_description($('textarea[name="index_description"]').val()));
			}
			else if( z_description($('textarea[name="index_description"]').val()) == true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').hide();
			}
		});
		$('input[name="index_robots"]').keyup(function(){
			if( kb.test($('input[name="index_keywords"]').val()) ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text('首页关键词为空！');
			}
			else if( z_robots($('input[name="index_robots"]').val()) != true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').show();
				$('#index_m>div.message .info').text(z_robots($('input[name="index_robots"]').val()));
			}
			else if( z_robots($('input[name="index_robots"]').val()) == true ){
				$('#index_m>div.message').removeClass('Warning danger');
				$('#index_m>div.message').addClass('Warning').hide();
			}
		});
		$('input[name="sitemap_exclude_cat"]').keyup(function(){
			if( z_id($('input[name="sitemap_exclude_cat"]').val()) != true ){
				$('#sitemap_m>div.message').removeClass('Warning danger');
				$('#sitemap_m>div.message').addClass('Warning').show();
				$('#sitemap_m>div.message .info').text(z_id($('input[name="sitemap_exclude_cat"]').val()));
			}
			else if( z_id($('input[name="sitemap_exclude_cat"]').val()) == true ){
				$('#sitemap_m>div.message').removeClass('Warning danger');
				$('#sitemap_m>div.message').addClass('Warning').hide();
			}
		});
		$('input[name="sitemap_exclude_tag"]').keyup(function(){
			if( z_id($('input[name="sitemap_exclude_tag"]').val()) != true ){
				$('#sitemap_m>div.message').removeClass('Warning danger');
				$('#sitemap_m>div.message').addClass('Warning').show();
				$('#sitemap_m>div.message .info').text(z_id($('input[name="sitemap_exclude_tag"]').val()));
			}
			else if( z_id($('input[name="sitemap_exclude_tag"]').val()) == true ){
				$('#sitemap_m>div.message').removeClass('Warning danger');
				$('#sitemap_m>div.message').addClass('Warning').hide();
			}
		});
		$('#index_button').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_index',
				index_title : $("input[name='index_title']").val(),
				index_keywords : $("input[name='index_keywords']").val(),
				index_description : $("textarea[name='index_description']").val(),
				index_robots : $("input[name='index_robots']").val()
			},
			type:'post',
			cache:false,
			success:function(data) {
				if( data.up == 200 ){
					$('#index_m>div.message').removeClass('Warning danger');
					$('#index_m>div.message').show();
					$('#index_m>div.message .info').text('设置成功');
					$('#index_m>div.message').hide(6000);
				}
				else if( data.up == 404 ){
					$('#index_m>div.message').removeClass('Warning danger');
					$('#index_m>div.message').addClass('danger').show();
					$('#index_m>div.message .info').text('保存失败，所有设置值均为空白或空白字符！');
				}
				else if( data.up == 500 ){
					$('#index_m>div.message').removeClass('Warning danger');
					$('#index_m>div.message').addClass('danger').show();
					$('#index_m>div.message .info').text('保存失败，请刷新后重试！');
				}
			},
			error : function() {
					$('#index_m>div.message').removeClass('Warning danger');
					$('#index_m>div.message').addClass('danger').show();
					$('#index_m>div.message .info').text('保存失败，服务器异常！');
			}
			});
		});
		$('#sitemap_button').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_sitemap',
				sitemap_exclude_cat : $('input[name="sitemap_exclude_cat"]').val(),
				sitemap_exclude_tag : $('input[name="sitemap_exclude_tag"]').val(),
				sitemap_exclude_single : $('input[name="sitemap_exclude_single"]').val(),
				sitemap_html : $('input[name="sitemap_html"]:checked').val(),
				sitemap_auto : $('input[name="sitemap_auto"]:checked').val()
			},
			type:'post',
			cache:false,
			success:function(data) {
				if( data.up == 200 ){
					$('#sitemap_m>div.message').removeClass('Warning danger');
					$('#sitemap_m>div.message').show();
					$('#sitemap_m>div.message .info').text('设置成功');
					$('#sitemap_m>div.message').hide(6000);
				}
				else if( data.up == 500 ){
					$('#sitemap_m>div.message').removeClass('Warning danger');
					$('#sitemap_m>div.message').addClass('danger').show();
					$('#sitemap_m>div.message .info').text('保存失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#sitemap_m>div.message').removeClass('Warning danger');
				$('#sitemap_m>div.message').addClass('danger').show();
				$('#sitemap_m>div.message .info').text('保存失败，服务器异常！');
			}
			});
	   });
	   $('#push_button').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_push',
				push_key : $('input[name="push_key"]').val(),
				push_auto : $('input[name="push_auto"]:checked').val()
			},
			type:'post',
			cache:false,
			success:function(data) {
				if( data.up == 200 ){
					$('#push_m>div.message').removeClass('Warning danger');
					$('#push_m>div.message').show();
					$('#push_m>div.message .info').text('设置成功');
					$('#push_m>div.message').hide(6000);
				}
				else if( data.up == 500 ){
					$('#push_m>div.message').removeClass('Warning danger');
					$('#push_m>div.message').addClass('danger').show();
					$('#push_m>div.message .info').text('保存失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#push_m>div.message').removeClass('Warning danger');
				$('#push_m>div.message').addClass('danger').show();
				$('#push_m>div.message .info').text('保存失败，服务器异常！');
			}
			});
	   });
	    $('#robots_button').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_robots',
				sa_robots : $("textarea[name='sa_robots']").val()
			},
			type:'post',
			cache:false,
			success:function(data) {
			if( data.up == 200 ){
					$('#robots_m>div.message').removeClass('Warning danger');
					$('#robots_m>div.message').show();
					$('#robots_m>div.message .info').text('设置成功');
					$('#robots_m>div.message').hide(6000);
				}
				else if( data.up == 500 ){
					$('#robots_m>div.message').removeClass('Warning danger');
					$('#robots_m>div.message').addClass('danger').show();
					$('#robots_m>div.message .info').text('保存失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#robots_m>div.message').removeClass('Warning danger');
				$('#robots_m>div.message').addClass('danger').show();
				$('#robots_m>div.message .info').text('保存失败，服务器异常！');
			}
			});
	   });
	   $('#robots_button_mk').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_mkrobots'
			},
			type:'post',
			cache:false,
			success:function(data) {
			if( data.mk == 200 ){
					$('#robots_m>div.message').removeClass('Warning danger');
					$('#robots_m>div.message').show();
					$('#robots_m>div.message .info').text('生成robots.txt成功');
					$('#robots_m>div.message').hide(6000);
				}
			else if( data.mk == 500 ){
					$('#robots_m>div.message').removeClass('Warning danger');
					$('#robots_m>div.message').addClass('danger').show();
					$('#robots_m>div.message .info').text('生成robots.txt失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#robots_m>div.message').removeClass('Warning danger');
				$('#robots_m>div.message').addClass('danger').show();
				$('#robots_m>div.message .info').text('生成robots.txt失败，服务器异常！');
			}
			});
	   });
	   $('#sitemap_button_mk').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_mksitemap'
			},
			type:'post',
			cache:false,
			success:function(data) {
			if( data.mk == 200 ){
					$('#sitemap_m>div.message').removeClass('Warning danger');
					$('#sitemap_m>div.message').show();
					$('#sitemap_m>div.message .info').text('生成站点地图成功成功');
					$('#sitemap_m>div.message').hide(6000);
				}
			else if( data.mk == 300 ){
					$('#sitemap_m>div.message').removeClass('Warning danger');
					$('#sitemap_m>div.message').addClass('danger').show();
					$('#sitemap_m>div.message .info').text('生成xml地图失败，请刷新后重试！');
				}
			else if( data.mk == 400 ){
					$('#sitemap_m>div.message').removeClass('Warning danger');
					$('#sitemap_m>div.message').addClass('danger').show();
					$('#sitemap_m>div.message .info').text('生成html地图失败，请刷新后重试！');
				}
			else if( data.mk == 500 ){
					$('#sitemap_m>div.message').removeClass('Warning danger');
					$('#sitemap_m>div.message').addClass('danger').show();
					$('#sitemap_m>div.message .info').text('生成站点地图失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#sitemap_m>div.message').removeClass('Warning danger');
				$('#sitemap_m>div.message').addClass('danger').show();
				$('#sitemap_m>div.message .info').text('生成站点地图失败，服务器异常！');
			}
			});
	   });
	   $('#global_button').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_global',
				global_title : $("input[name='global_title']").val(),
				global_nofollow : $('input[name="global_nofollow"]:checked').val(),
				global_linkgo : $('input[name="global_linkgo"]:checked').val(),
				global_imgalt : $('input[name="global_imgalt"]:checked').val(),
				global_desc : $('input[name="global_desc"]:checked').val(),
				global_plnofollow : $('input[name="global_plnofollow"]:checked').val(),
				global_pllinkgo : $('input[name="global_pllinkgo"]:checked').val(),
				global_date : $('input[name="global_date"]:checked').val(),
				global_archive : $('input[name="global_archive"]:checked').val()
			},
			type:'post',
			cache:false,
			success:function(data) {
			if( data.up == 200 ){
					$('#global_m>div.message').removeClass('Warning danger');
					$('#global_m>div.message').show();
					$('#global_m>div.message .info').text('更新设置成功！');
					$('#global_m>div.message').hide(6000);
				}else{
					$('#global_m>div.message').removeClass('Warning danger');
					$('#global_m>div.message').addClass('danger').show();
					$('#global_m>div.message .info').text('更新设置失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#global_m>div.message').removeClass('Warning danger');
				$('#global_m>div.message').addClass('danger').show();
				$('#global_m>div.message .info').text('更新设置失败，服务器异常！');
			}
			});
	   });
	   $('#other_button').on('click',function(){
			$.ajax( {
			url: ajaxurl,
			data:{
				action : 'sa_other',
				other_taxonomy : $("input[name='other_taxonomy']").val(),
				other_category : $('input[name="other_category"]:checked').val()
			},
			type:'post',
			cache:false,
			success:function(data) {
			if( data.up == 200 ){
					$('#other_m>div.message').removeClass('Warning danger');
					$('#other_m>div.message').show();
					$('#other_m>div.message .info').text('更新设置成功！');
					$('#other_m>div.message').hide(6000);
				}else{
					$('#other_m>div.message').removeClass('Warning danger');
					$('#other_m>div.message').addClass('danger').show();
					$('#other_m>div.message .info').text('更新设置失败，请刷新后重试！');
				}
			},
			error : function() {
				$('#global_m>div.message').removeClass('Warning danger');
				$('#global_m>div.message').addClass('danger').show();
				$('#global_m>div.message .info').text('更新设置失败，服务器异常！');
			}
			});
	   });
});
});