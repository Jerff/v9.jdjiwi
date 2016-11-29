<?php


class _backup_site_list_db extends driver_db_list {

    protected function getTable() {
		return db_backup_site;
	}

    public function loadData(&$row) {
		if(isset($row['date'])) $row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

	protected function getSort() {
		return array('time', 'name');
	}

	public function updateData($list, $send) {
        if(isset($send['name']) or isset($send['time'])) {
			$this->command()->reloadView();
        }
	}

}

?>