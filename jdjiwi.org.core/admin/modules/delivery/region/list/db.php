<?php


class delivery_region_list_db extends driver_db_list_one {

	public function returnParent() {
		return 'delivery_region_edit_db';
	}

	protected function getTable() {
		return db_delivery_region;
	}

	protected function getSort() {
	    return array('pos', 'name');
	}

}

?>