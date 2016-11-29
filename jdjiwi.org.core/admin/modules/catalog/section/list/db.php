<?php


class catalog_section_list_db extends driver_db_list_one {

	public function returnParent() {
		return 'catalog_section_edit_db';
	}

	protected function getTable() {
		return db_section;
	}

}

?>