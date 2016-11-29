<?php


class catalog_section_edit_db extends driver_db_edit_tree {

    public function updateController() {
		return 'model_catalog_section';
	}

	protected function getTable() {
		return db_section;
	}

	protected function loadFormFilterParent() {
		return array('level<3');
	}

}

?>