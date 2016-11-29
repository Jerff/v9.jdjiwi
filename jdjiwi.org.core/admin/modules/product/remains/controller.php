<?php


class product_remains_controller extends product_list_controller {

	protected function init() {
		parent::init();
		$this->initModul('main',	'product_remains_modul');

		// url
		$this->url()->setSubmit('/admin/product/remains/');
		$this->url()->setEdit('/admin/product/edit/');
	}

	public function getBasketId($section) {
		static $_section = array();
        if(!$section) return ;
		if(isset($_section[$section])) {
            return $_section[$section];
        }
        list($parent, $basket) = $this->sql()->placeholder("SELECT parent, basket FROM ?t WHERE id=?", db_section, $section)
                                 ->fetchRow();
        if($basket) {
            $value = $this->sql()->placeholder("SELECT value FROM ?t WHERE id=?", db_param, $basket)
                                    ->fetchRow(0);
            return $_section[$section] = array($basket, cConvert::unserialize($value));
        }
        return $_section[$section] = $this->getBasketId($parent);
	}

}

?>