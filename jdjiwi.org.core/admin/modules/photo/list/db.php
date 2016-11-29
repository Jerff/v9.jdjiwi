<?php


class photo_list_db extends driver_db_list {

	public function returnParent() {
		return 'photo_edit_db';
	}

	protected function getTable() {
		return db_photo;
	}

	protected function getSort() {
		return array('isMain'=>'ASC', 'date'=>'DESC');
	}

	protected function getFields() {
		return array('id', 'date','header', 'uri', 'isMain', 'visible');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

	public function updateData($list, $send) {
        if(isset($send['isMain'])) {
			$this->command()->reloadView();
        }
	}


}

?>