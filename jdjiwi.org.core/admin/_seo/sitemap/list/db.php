<?php


class _seo_sitemap_list_db extends driver_db_list {

	protected function getTable() {
		return db_seo_sitemap;
	}

	public function updateData($list, $send) {
	}

}

?>