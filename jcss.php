<?php
	/*
		Plugin Name: JCSS
		Plugin URI: http://www.zhifangzi.com/jcss.html
		Description: jcss is a javascript and css file compier.
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
				echo '<script type="text/javascript" charset="utf-8" src="321.js"></script>';
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