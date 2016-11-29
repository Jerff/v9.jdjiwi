<?php


class _mail_templates_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_mail_templates;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('mail');
	}

}

?>