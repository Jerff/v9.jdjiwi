<?php


class _seo_counters_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_seo_counters;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('seoCounters');
	}

}

?>