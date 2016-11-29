<?php

list($header, $header2, $footer2, $footer) = $this->runAll('/header/', '/info/header/', '/info/footer/', '/footer/');

echo    $header .
        $header2 .
        $content .
        $footer2 .
        $footer;

?>