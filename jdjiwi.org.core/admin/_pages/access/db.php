<?php


class _pages_access_db extends driver_db_list {

	protected function getTable() {
		return false;
	}

	// выборка данных записи из базы для стрницы
	public function runList($id=null, $offset=null, $limit=null) {
		return cAccessModul::getListModul();
	}

}

?>