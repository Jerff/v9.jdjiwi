<?php


class _seo_counters_list_db extends driver_db_list {

	public function returnParent() {
		return '_seo_counters_edit_db';
	}

	protected function getTable() {
		return db_seo_counters;
	}

}

?>