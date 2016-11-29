<?php

preg_match('~^<p>(.*)</p>$~smu', $_POST['text'], $tmp);
echo $tmp[1];
//echo (strip_tags($_POST['text'], '<nobr>'));
////echo (htmlspecialchars($_POST['text']));
//
//var_dump($tmp);

?>