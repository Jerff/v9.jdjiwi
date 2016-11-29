<?php


class _profil_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user;
	}

	public function save($send) {
		cmfModelAdmin::save($send, $this->id()->get());
		if(isset($send['name'])) {
			$this->ajax()->html('mainUserName', $send['name']);
        }
		$this->update()->set($send);
	}

	public function updateData($list, $send) {
	}

}

?>