<?php



$list = $this->load('list', 'dump_size_controller');
$config = $this->load('config', 'dump_config_controller', 'product.dump');
$this->assing('log', $list->getLog());
$this->processing();








?>
