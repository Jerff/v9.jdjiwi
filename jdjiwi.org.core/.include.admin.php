<?php

require('_.core/loader/cLoader.php');
cLoader::library('setting.template');
cLoader::library('loader/cAutoload');
cLoader::library('system/function');
cLoader::library('sql.table');

cLoader::library('input/cInput');
cLoader::library('debug/cConfig');



cLoader::library('pages/cPages');
cLoader::library('setting.pages');
cLoader::library('setting.application');
cLoader::library('page.admin');

cLoader::library('pagination/functionAdmin');
//cLoader::library('application/cApplication');
?>