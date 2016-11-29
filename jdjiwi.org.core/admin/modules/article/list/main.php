<?php


$list = $this->load('list', 'article_list_controller');
$this->assing('filterSection', $list->filterSection());
$config = $this->load('config', 'article_list_config_controller', 'article');
$this->processing();


$this->assing('attach', $list->attachProduct());


?>
