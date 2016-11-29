<?php


class subscribe_mail_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_subscribe_mail;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('subscribe', $this->id()->get());
	}


    public function setActive() {
        $data = array('visible'=>'yes');
        $this->save($data);
    }

}

?>