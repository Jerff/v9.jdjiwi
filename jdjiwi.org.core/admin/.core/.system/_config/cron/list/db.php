<?php


class _config_cron_list_db extends driver_db_list {

	protected function getTable() {
		return db_sys_cron;
	}

	public function loadData(&$row) {
		if(isset($row['date'])) $row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

	public function updateData($list, $send) {
	}

}

?>