<?php
header('Content-Type: text/html; charset=utf-8');
if (empty($_POST['text']))
{
	$out_txt = '';
}
else
{
require_once("typographus.php");
$typo = new typographus("UTF-8");

$in_txt = urldecode($_POST['text']);
$out_txt = $typo->process($in_txt);
}
echo $out_txt;
?>