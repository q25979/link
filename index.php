<style>
	.container { height: 350px; width: 400px; border: 1px solid #ECECEC; border-radius: 3px; margin: 10px 0; padding: 10px; overflow-y: scroll;}
</style>
<form action="">
	目录名：<input type="text" name="dir" value="map" placeholder="请输入目录名">
	<button>生成</button>
</form>

<?php
// 错误屏蔽
error_reporting(1);

/**
 * 读取文件
 * @param  string $name 文件名
 * @return string       读取文件的数据
 */
function openfile($name) 
{
	// 读取文件
	$file = fopen($name, 'r') or die('文件打开失败!');
	$sfile = fread($file, filesize($name));

	fclose($file);
	return $sfile;
}

/**
 * 生成文件
 * @param  string $dir 生成目录名称
 */
function build()
{
	$dir = $_GET['dir'];
	if (empty($dir)) return false;
	mkdir($dir) or die('目录生成失败,可能已经有此目录了,请更换目录名!');	// 创建目录
	echo '<div class="container">';
	$m = fopen('m.html', 'r');
	$s = fread($m, filesize('m.html'));

	// 获取得到的字符串分割成数组
	$title = explode("\n", openfile('title.txt'));
	$keywords = explode("\n", openfile('keywords.txt'));
	$desc = explode("\n", openfile('desc.txt'));

	// 替换关键字
	$str = str_replace('{url}', openfile('url.txt'), $s);
	$string = array();
	for ($i=0; $i<count($title); $i++) {
		$ik = $i >= count($keywords) ? count($keywords)-1 : $i; // 当关键词没有标题多时
		$id = $i >= count($desc) ? count($desc)-1 : $i;			// 当描述没有标题多时

		$string[$i] = str_replace('{title}', $title[$i], $str);
		$string[$i] = str_replace('{keywords}', $keywords[$ik], $string[$i]);
		$string[$i] = str_replace('{desc}', $desc[$id], $string[$i]);

		// 写入文件
		$filename = $dir.'/'.($i+1).'.html';
		$handle = fopen($filename, 'w');
		fwrite($handle, $string[$i]);
		fclose($handle);
		echo $filename."-----------------文件生成成功！<br/>";
	}
	echo '</div>';
	echo "文件全部生成完成。";
}

// 组件
build();
?>
