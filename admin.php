<?php
	/**
	 * 表单提交相关操作
	 */
	if( isset($_POST['jcss']) && $_POST['jcss'] == "zhifangzi.com" ){

		$jcss_option_array = array('js_links' => array(), 'css_links' => array());

		foreach( $_POST['js_links'] as $js_link ){
			if(!empty($js_link)){
				$jcss_option_array['js_links'][] = $js_link;
			}
		}
		
		foreach( $_POST['css_links'] as $css_link ){
			if(!empty($css_link)){
				$jcss_option_array['css_links'][] = $css_link;
			}
		}
		
		update_option('jcss_option_json', json_encode($jcss_option_array));
	}
	
	/**
	 * 在admin menu处增加jcss配置按钮及页面
	 */
	add_action('admin_menu', 'jcss_options_page');
	function jcss_admin_init(){	
		$jcss_option_array = json_decode(get_option('jcss_option_json'));
?>
<script type="text/javascript">
	function add_link_option(id, name){
		var input = '<p><input name="'+name+'[]" type="text" value="" class="regular-text" /></p>';
		jQuery('#'+id).append(input);
		return false;
	}
</script>
<div class="wrap">
	<h2>jcss 配置</h2>
	<form action="" method="post" id="jcss_form">
		<input type="hidden" value="zhifangzi.com" name="jcss" />
		<h3>JS压缩模块</h3>
		<p>javascript文件的完整地址，可以是外引文件</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="js_links">JS文件地址</label></th>
					<td>
						<fieldset id="js_links">
							<?php
								foreach($jcss_option_array->js_links as $js_link){
									echo '<p><input name="js_links[]" type="text" value="'.$js_link.'" class="regular-text" /></p>';
								}
							?>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td><a class="button" onclick="add_link_option('js_links','js_links')">&#10010</a></td>
				</tr>
			</tbody>
		</table>
		<h3>CSS压缩模块</h3>
		<p>css文件的完整地址，可以是外引文件</p>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="css_links">CSS文件地址</label></th>
					<td>
						<fieldset id="css_links">
							<?php
								foreach($jcss_option_array->css_links as $css_link){
									echo '<p><input name="css_links[]" type="text" value="'.$css_link.'" class="regular-text" /></p>';
								}
							?>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"></th>
					<td><a class="button" onclick="add_link_option('css_links','css_links')">&#10010</a></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="保存更改"></p>
	</form>
</div>
<?php
	}
	function jcss_options_page() {
		if (function_exists('add_options_page')) {
			add_options_page('JCSS', 'JCSS', 8, basename(__FILE__), 'jcss_admin_init');
		}
	}
?>