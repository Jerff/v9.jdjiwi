<?php
cDebug::sqlOff();


$this->assing('_menu', cAdmin::menu()->get());
$this->assing('updateUrl', cUrl::admin()->command('/admin/cache/data/'));
$this->assing('name', cAdmin::user()->get('name'));
$this->assing('profilUrl', cUrl::admin()->get('/admin/profil/'));


cDebug::sqlOn();
?>