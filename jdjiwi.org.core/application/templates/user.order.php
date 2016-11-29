<?php

list($header, $header2, $footer) = $this->runAll('/header/', '/order/header/', '/footer/');

echo    $header .
        $header2 .
        $content .
        $footer;

?>