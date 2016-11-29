<?php


class _seo_reklama_list_db extends driver_db_list {

	public function returnParent() {
		return '_seo_reklama_edit_db';
	}

	protected function startSaveWhere() {
		return array('type');
	}

	protected function getTable() {
		return db_seo_reklama;
	}

	protected function getSort() {
		return array('type', 'pos');
	}

}

?>