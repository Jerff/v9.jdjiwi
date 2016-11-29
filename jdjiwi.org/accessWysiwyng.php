<?php

define('cApplication', 'wysiwyng');

$old = getcwd();
chdir(__DIR__);
$path = require('index.php');

chdir($old);
return $path;
?>