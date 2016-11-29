<?php


class _mail_var_config_db extends driver_db_edit {


	protected function getTable() {
		return db_mail_config;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('mail');
	}

}

?>