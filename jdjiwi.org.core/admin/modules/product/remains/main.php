<?php



$list = $this->load('list', 'product_remains_controller');
$this->assing('filterSection', $list->filterSection());
$this->assing('filterBrand', $list->filterBrand());
$this->assing('filterFilter', $list->filterFilter());
$this->processing();


$this->assing('articul', htmlspecialchars($list->getFilter('articul')));
$this->assing('price1', htmlspecialchars($list->getFilter('price1')));
$this->assing('price2', htmlspecialchars($list->getFilter('price2')));



$this->assing('section', $list->getFilter('section'));
$this->assing('attach', $list->attachProduct());





?>
