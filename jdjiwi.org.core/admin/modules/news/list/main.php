<?php

$list = $this->load('list', 'news_list_controller');
$list->initSection();
$config = $this->load('config', 'news_list_config_controller', 'news');
$this->processing();
?>
