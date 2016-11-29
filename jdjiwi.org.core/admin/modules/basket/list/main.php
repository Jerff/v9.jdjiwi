<?php



$list = $this->load('list', 'basket_list_controller');
$this->assing('filterType', $list->filterType());
$this->processing();

$this->assing('listUser', $list->listUser());





?>
