<?php


class subscribe_history_view_db extends driver_db_edit {

	protected function getTable() {
		return db_subscribe_history;
	}

    public function loadData(&$row) {
		$row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

    protected function getWhereFilter() {
		return array('subscribe'=> cAdmin::menu()->sub()->getId());
	}

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'subscribe'=> cAdmin::menu()->sub()->getId());
	}

}

?>