<?php


class user_param_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user_data;
	}

	public function updateData($list, $send) {
	    if(isset($send['subscribe']) or isset($send['shop'])) {
            list($subscribe, $shop) = cLoader::initModul('user_param_edit_db')->getFeildsRecord(array('subscribe', 'shop'), $this->id()->get());
            list($email) = cLoader::initModul('user_edit_db')->getFeildsRecord(array('email'), $this->id()->get());
            if($subscribe==='yes') {
                cmfSubscribe::addUser($email, $shop, $this->id()->get());
            } else {
                cmfSubscribe::delUser($this->id()->get());
            }
	    }
	}

}

?>