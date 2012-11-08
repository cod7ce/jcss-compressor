<?php
	/**
	 * 表单提交相关操作
	 */
	if( isset($_POST['jcss']) && $_POST['jcss'] == "zhifangzi.com" ){

		// 初始化配置信息
		$jcss_option_array = array('js_links' => array(), 'css_links' => array(), 'save_path' => '');
		$chmod = 0755;

		// 压缩后的js与css文件保存位置
		$root_dir = dirname(dirname(dirname(dirname(__FILE__))));
		$file_dir = "wp-content/plugins/jcss";
		if(isset($_POST['save_path']) && !empty($_POST['save_path'])){
			$jcss_option_array['save_path'] = $_POST['save_path'];
			$file_dir = $_POST['save_path'];
		}
		$file_dir = str_replace("/", DIRECTORY_SEPARATOR, $file_dir);
		$js_file =  $root_dir. DIRECTORY_SEPARATOR . $file_dir . DIRECTORY_SEPARATOR . 'jcss.js';
		$css_file =  $root_dir. DIRECTORY_SEPARATOR . $file_dir . DIRECTORY_SEPARATOR . 'jcss.css';

		// 创建不存在的目录
		if (!is_dir($root_dir. DIRECTORY_SEPARATOR . $file_dir)){ 
			if (!mkdir($root_dir. DIRECTORY_SEPARATOR . $file_dir, $chmod, 1)){ 
				echo "目录创建失败，看看权限吧~~";
			} 
		}

		// js连接地址集赋值
		if(isset($_POST['js_links'])){
			foreach( $_POST['js_links'] as $js_link ){
				if(!empty($js_link)){
					$jcss_option_array['js_links'][] = $js_link;
				}
			}
		}
		// 向js文件中写入数据
		if( count($jcss_option_array['js_links']) ){
			if($handle = fopen($js_file, 'w')){
				foreach($jcss_option_array['js_links'] as $v){
					fwrite($handle, jcss_exec_curl($v));
				}
				fclose($handle);
			}
		} else {
			if( file_exists($js_file))
				unlink($js_file);
		}
		
		// css连接地址集赋值
		if(isset($_POST['css_links'])){
			foreach( $_POST['css_links'] as $css_link ){
				if(!empty($css_link)){
					$jcss_option_array['css_links'][] = $css_link;
				}
			}
		}
		// 向css文件中写入数据
		if( count($jcss_option_array['css_links']) ){
			if($handle = fopen($css_file, 'w')){
				foreach($jcss_option_array['css_links'] as $v){
					fwrite($handle, jcss_exec_curl($v));
				}
				fclose($handle);
			}
		} else {
			if( file_exists($css_file))
				unlink($css_file);
		}

		update_option('jcss_option_json', json_encode($jcss_option_array));
	}
	
	/**
	 * 在admin menu处增加jcss配置按钮及页面
	 */
	add_action('admin_menu', 'jcss_options_page');
	function jcss_admin_init(){	
		$json_string = get_option('jcss_option_json')?get_option('jcss_option_json'):'{"js_links":[],"css_links":[],"save_path":""}';
		$jcss_option_array = json_decode($json_string);
?>
<script type="text/javascript">
	function add_link_option(id, name){
		var input = '<p><input name="'+name+'[]" type="text" value="" class="regular-text" /></p>';
		jQuery('#'+id).append(input);
		return false;
	}
</script>
<div class="wrap">
	<div id="icon-link-manager" class="icon32"><br></div>
	<h2>jcss 配置</h2>
	<form action="" method="post" id="jcss_form">
		<input type="hidden" value="zhifangzi.com" name="jcss" />
		<h3>JS压缩模块</h3>
		<p>javascript文件的完整地址，可以是外引文件，请注意js文件顺序。</p>
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
		<p>css文件的完整地址，可以是外引文件，请注意css文件顺序。</p>
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
		<h3>可选</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="upload_path">压缩文件存放路径</label></th>
					<td><input name="save_path" type="text" id="upload_path" value="<?php echo $jcss_option_array->save_path; ?>" class="regular-text code">
					<p class="description">默认为 <code>wp-content/plugins/jcss</code></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="保存更改">&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://me.alipay.com/cod7ce" target="_blank">支付宝捐赠</a></p>
	</form>
</div>
<?php
	}
	function jcss_options_page() {
		if (function_exists('add_options_page')) {
			add_options_page('JCSS', 'JCSS', 8, basename(__FILE__), 'jcss_admin_init');
		}
	}

	function jcss_exec_curl($url="") {
		$contents = "";
		if( $url != "" ) {
			// 创建一个cURL资源
			$ch = curl_init();

			// 设置URL和相应的选项
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			//curl_setopt($ch, OPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


			// 抓取URL并把内容传递给文档流
			$contents = curl_exec($ch);

			// 关闭cURL资源，并且释放系统资源
			curl_close($ch);
		}
		return $contents;
	}
?>