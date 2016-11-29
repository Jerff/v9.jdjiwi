<?php


$list = $this->load('list', 'article_attach_controller');

if(!$menu = $list->filterMenu()) $this->command()->noRecord();
$this->assing('menu', $menu);

$this->assing('filterSection', $list->filterSection());
$this->assing('filterBrand', $list->filterBrand());

$this->assing('filterAttach', $list->filterAttach());
$this->assing('filterFilter', $list->filterFilter());

$this->processing();


$this->assing('articul', htmlspecialchars($list->getFilter('articul')));
$this->assing('price1', htmlspecialchars($list->getFilter('price1')));
$this->assing('price2', htmlspecialchars($list->getFilter('price2')));

$this->assing('section', $list->getFilter('section'));
$this->assing('productId', $list->getFilter('article'));






?>