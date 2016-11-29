<?php

require('_.core/loader/cLoader.php');
cLoader::library('setting.template');
cLoader::library('system/function');

cLoader::library('register/cRegister');
cLoader::library('input/cInput');

cLoader::library('session/cSession');
cLoader::library('settings/cSettings');

cLoader::library('file/cDir');
cLoader::library('file/cFile');

cLoader::library('pages/cPages');
cLoader::library('setting.pages');
cLoader::library('setting.application');
cLoader::library('sql.table');

cLoader::library('cache/cmfCache');
cLoader::library('cache/cmfCacheSite');
cLoader::library('cache/cmfCacheUser');

cLoader::library('admin/cAdmin');

cLoader::library('debug/cConfig');
cLoader::library('compile/cCompile');
cLoader::library('application/cApplication');
?>