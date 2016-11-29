<?php

list($header, $footer) = $this->runAll('/print/header/', '/print/footer/');

echo    $header .
        $content .
        $footer;

?>