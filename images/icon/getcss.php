<?php
$dir = './';
$list = dir_map($dir);
$arr = array();
foreach($list as $v){
	$path = pathinfo($v);
	if($path['extension']=='gif' || $path['extension']=='png'){
		$arr[] = "icon-{$path['filename']}";
		echo ".icon-{$path['filename']}{background-image: url(../images/icon/{$path['filename']}.{$path['extension']}) !important;}\n";
	}
}

echo "\n\n\n\n\n\n\n";
echo implode(",",$arr);










// 返回文件夹地图
function dir_map($source_dir, $top_level_only = true)
{	
	if ($fp = @opendir($source_dir))
	{
		$source_dir = rtrim(rtrim($source_dir, '/'),'\\').DIRECTORY_SEPARATOR;		
		$filedata = array();
		
		while (FALSE !== ($file = readdir($fp)))
		{
			if (strncmp($file, '.', 1) == 0)
			{
				continue;
			}

			if ($top_level_only == FALSE && @is_dir($source_dir.$file))
			{
				$temp_array = array();
			
				$temp_array = self::dir_map($source_dir.$file.DIRECTORY_SEPARATOR);
			
				$filedata[$file] = $temp_array;
			}
			else
			{
				$filedata[] = $file;
			}
		}
		
		closedir($fp);
		return $filedata;
	}
}