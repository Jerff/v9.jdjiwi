<?php



$edit = $this->load('edit', '_seo_title_controller');
$config = $this->load('config', '_seo_title_config_controller', 'seo');
$edit->id()->set(urldecode($edit->id()->get()));
$this->processing();











?>
