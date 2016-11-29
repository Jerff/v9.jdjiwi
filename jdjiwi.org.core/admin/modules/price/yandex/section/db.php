<?php


class price_yandex_section_db extends driver_db_edit_product_action {

	function __construct() {
		$this->setIdName('main');
		parent::__construct();
	}

	protected function getTable() {
		return db_price_yandex;
	}

	protected function getActionList() {
		return cLoader::initModul('catalog_section_list_tree')->getNameList();
	}

	protected function action() {
		return 'section';
	}

	public function save($send) {
		if($send) {
			$action = $this->action();
			foreach($this->getActionKey() as $key) {
				if(!isset($send[$key])) continue;
				$value = get($send, $key);
				if($value==='yes') {
					$this->sql()->del($this->getTable(), array($action=>$key));
				} elseif($value==='no') {
					$this->sql()->replace($this->getTable(), array($action=>$key));
				}
			}
		}
	}

	protected function getWhere() {
		return array(1);
	}

	public function updateData($list, $send) {
	}

}

?>