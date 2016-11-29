<?php

$old = getcwd();
chdir(__DIR__);

define('cApplication', 'wysiwyng');
$path = require('index.php');

chdir($old);
return $path;
?>