<?php



$list = $this->load('list', 'baner_catalog_controller');
$config = $this->load('config', 'baner_config_controller', 'baner');
$this->assing('filterSection', $list->filterSection());
$this->assing('filterBrand', $list->filterBrand());
$this->processing();







?>
