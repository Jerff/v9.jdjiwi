<?php


abstract class driver_db_list_one extends driver_db_list {

	protected function getWhereFilter() {
		return array('parent'=>(int)$this->getFilter('list'));
	}

	protected function startSaveWhere() {
		return array('parent');
	}

	public function &getCount(&$list_id) {
		$data = array();
		return $data;
	}

}

?>