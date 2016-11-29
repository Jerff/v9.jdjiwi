<?php

list($header, $header2, $footer2, $footer) = $this->runAll('/header/', '/catalog/header/', '/catalog/footer/', '/footer/');

echo 	$header .
		$header2 .
		$content .
		$footer2 .
		$footer;

?>