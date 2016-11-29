<?php


class price_import_edit_db extends driver_db_edit {

    public function loadData(&$row) {
        $row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

	protected function getTable() {
		return db_shop_import;
	}

}

?>