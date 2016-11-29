<?php

list($header, $footer) = $this->runAll('/header/', '/footer/');

echo 	$header .
		$content .
		$footer;

?>