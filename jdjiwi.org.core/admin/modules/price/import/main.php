<?php



$edit = $this->load('edit', 'price_import_controller');
$this->assing('filterShop', $edit->filterShop());
if($edit->getFilter('command')==='export') {
	$edit->export();
	exit;
}
$list = $this->load('list', 'price_import_list_controller');
$this->processing();



$this->assing('is', $edit->isWrite());
$this->assing('shop', $edit->shop());





?>
