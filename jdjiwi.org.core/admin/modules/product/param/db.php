<?php


class product_param_db extends driver_db_edit_param {

    public function updateController() {
		return 'model_product';
	}

    public function returnParent() {
		return 'product_edit_db';
	}

	protected function init() {
		parent::init();
    }

	protected function getTable() {
		return db_product;
	}

    public function isShop($id, $shop) {
        return $this->sql()->placeholder("SELECT 1 FROM ?t WHERE id=? AND shop=?", db_product, $this->id()->get(), $shop)
									->numRows();
	}

	public function paramList() {
		return cLoader::initModul('param_list_db')->getParamList($this->getTypeProduct(), array('visible'=>'yes'));
	}

	protected function getTypeProduct() {
		$type = cLoader::initModul('product_edit_db')->getSection($this->id()->get());
		cGlobal::set('$typeProduct', $type);
		return $type;
	}

	public function saveProductPrice($send) {
        cLoader::initModul('product_edit_db')->save($send);
	}

}

?>