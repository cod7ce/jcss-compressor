<?php
	/*
		Plugin Name: JCSS
		Plugin URI: http://www.zhifangzi.com/jcss.html
		Description: jcss是一款简单的js、css文件集压缩插件，可以有效减少资源请求数。 （jcss is a javascript and css file compier.）&nbsp;&nbsp;&nbsp; <a href="http://me.alipay.com/cod7ce" target="_blank">支付宝捐赠</a> &nbsp;&nbsp;&nbsp; <a href="http://github.com/cod7ce/jcss" target="_blank">Fork me on Github</a>
		Version: 1.0
		Author: cod7ce/大楠
		Author URI: http://www.zhifangzi.com
	*/
	/*  
		Copyright 2012  cod7ce/大楠  (email : cod7ce@gmail.com)

	    This program is free software; you can redistribute it and/or modify
	    it under the terms of the GNU General Public License as published by
	    the Free Software Foundation; either version 2 of the License, or
	    (at your option) any later version.

	    This program is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.

	    You should have received a copy of the GNU General Public License
	    along with this program; if not, write to the Free Software
	    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	if(!class_exists('JCSS')){
		class JCSS{
			function jcss_e(){
				$root_dir = dirname(dirname(dirname(dirname(__FILE__))));
				$jcss_option_array = json_decode(get_option('jcss_option_json'));
				$save_path = $jcss_option_array->save_path==''?'wp-content/plugins/jcss':$jcss_option_array->save_path;
				$resource_url = get_option('siteurl').'/'.$save_path;

				$save_path = str_replace("/", DIRECTORY_SEPARATOR, $save_path);
				$js_file =  $root_dir. DIRECTORY_SEPARATOR . $save_path . DIRECTORY_SEPARATOR . 'jcss.js';
				$css_file =  $root_dir. DIRECTORY_SEPARATOR . $save_path . DIRECTORY_SEPARATOR . 'jcss.css';
				
				if(file_exists($js_file))
					echo '<script type="text/javascript" charset="utf-8" src="'.$resource_url.'/jcss.js"></script>';
				if(file_exists($css_file))
					echo '<link rel="stylesheet" type="text/css" href="'.$resource_url.'/jcss.css">';
			}
		}
	}
	if(class_exists('JCSS')){
		$jcss = new JCSS();
	}

	if(isset ($jcss)){
    	add_action('wp_head', array(&$jcss, 'jcss_e'), 1);
	}

	/**
	 * 添加管理设置页面
	 */
	if( is_admin() )
	{
		include_once dirname( __FILE__ ) . '/admin.php';
	}
?>