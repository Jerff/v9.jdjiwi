<?php


class _administrator_config_db extends driver_db_list {

	protected function getTable() {
		return db_user;
	}

	protected function getFields() {
		return array('id', 'name', 'login', 'visible', 'debugError', 'debugSql', 'debugExplain', 'debugCache');
	}

	protected function getSort() {
		return array('login');
	}

	protected function getWhereFilter() {
		$admin = (int)$this->getFilter('admin');
		switch($admin) {
			case 0:
				return array("(LENGTH(`admin`)>1)");

			case -1:
				return array("`admin`='[0]'");

            default:
            	return array("`admin` LIKE '%[$admin]%'");
		}
	}

}

?>