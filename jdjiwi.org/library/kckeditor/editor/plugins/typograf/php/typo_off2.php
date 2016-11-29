<?php
header('Content-Type: text/html; charset=utf-8');

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '\library');


if (empty($_POST['text']))
{
	$out_txt = '';
}
else
{

$in_txt = urldecode($_POST['text']);
			require_once 'Jare/Typograph.php';
			$out_txt = Jare_Typograph::quickParse($in_txt);
}
echo $out_txt;
?>