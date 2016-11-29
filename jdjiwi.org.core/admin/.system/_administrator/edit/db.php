<?php


class _administrator_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user;
	}

	public function save($send) {
		$id = cmfAdminModel::save($send, $this->id()->get());
		$this->saveSetId($id);
		if(cRegister::adminId()==$id) {
            if(isset($send['name'])) {
				$this->ajax()->html('mainUserName', $send['name']);
            }
            if(isset($send['login']) or isset($send['password']) or isset($send['admin'])) {
            	$this->command()->reload();
			}
		}
		$this->saveEnd($id, $send);
		$this->update()->set($id, $send);
	}

	public function updateData($list, $send) {
	}

}

?>