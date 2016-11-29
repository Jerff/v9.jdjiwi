<?php


class price_import_controller extends driver_controller_list {

	protected function init() {
		parent::init();

		// url
		$this->url()->setSubmit('/admin/price/import/');

		$this->access()->writeAdd('import|export');
	}

	public function filterShop() {
		$filter = cLoader::initModul('shop_list_db')->getNameList();
		return parent::abstractFilter($filter, 'shop', 'end');
	}

	public function shop() {
		return cLoader::initModul('shop_edit_db')->getDataRecord($this->getFilter('shop'));
	}

	public function exportUrl() {
		return cUrl::admin()->command('/admin/price/import/') .'&command=export&shop='. $this->getFilter('shop');
	}


	static protected function encode($text) {
        return cConvert::translate($text);
	}
	public function export() {
	    if(!$this->isWrite()) return;
	    $shop = cLoader::initModul('shop_edit_db')->getDataRecord($this->getFilter('shop'));
	    if(!$shop) return;

        new cmfPriceExport($shop['id'], $shop['uri']);
        die();
	}

	public function import() {
	    if(!$this->isWrite()) return;
	    $shop = cLoader::initModul('shop_edit_db')->getDataRecord($this->getFilter('shop'));
	    if(!$shop) return;

        if(get2($_FILES, 'upload', 'error')!==0) {
            $this->ajax()->alert('Файл не загружен');
            return;
        }
        new cmfPriceImport($shop['id'], $_FILES['upload']['tmp_name']);
        $this->command()->reloadView();
	}

	public function isWrite() {
        $is = (int)cAdmin::user()->shop;
        if($is) {
            $shop = (int)$this->getFilter('shop');
            return $shop==$is;
        } else {
            return true;
        }
	}

}

?>