<?php

list($header, $footer) = $this->runAll('/header/', '/index/footer/');

echo 	$header .
		$content .
		$footer;

?>