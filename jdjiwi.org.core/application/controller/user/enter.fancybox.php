<?php

include_once(cmfModel . 'header.include.php');

cLoader::library('user/cUserEnter');
$this->assing2('userEnter', new cUserEnter('leftUserEnter'));
$this->assing2('passwordUrl', cUrl::get('/user/password/'));
///cDebug::destroy();
?>