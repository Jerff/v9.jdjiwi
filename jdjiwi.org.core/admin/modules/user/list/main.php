<?php



$list = $this->load('list', 'user_list_controller');
$config = $this->load('config', 'user_config_controller', 'user');
$this->processing();


$this->assing('name', htmlspecialchars(urldecode($list->getFilter('name'))));
$this->assing('email', htmlspecialchars(urldecode($list->getFilter('email'))));










?>
