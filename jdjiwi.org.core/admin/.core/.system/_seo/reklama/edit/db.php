<?php


class _seo_reklama_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_seo_reklama;
	}

	protected function startSaveWhere() {
		return array('type');
	}

	public function loadData(&$data) {
		$uri = $data['uri'];
		$uri = explode('][', substr($uri, 1, -1));
		$data['uri'] = implode("\r\n", $uri);
	}

	public function updateData($list, $send) {
	}

}

?>