<?php


class _pages_access_delete_db extends driver_db_list_product_attach {

	protected function getTable() {
		return db_access_delete;
	}

	protected function attach()  {
		return 'group';
	}

	protected function product()  {
		return 'modul';
	}

	public function updateData($list, $send) {
		if($send) {
			cAdmin::cache()->deleteTag('access');
		}
	}

	public function getPagesRead() {
		$_admin = cLoader::initModul('_administrator_group_list_db')->getIdList();

		return $this->sql()->placeholder("SELECT * FROM ?t WHERE `group` ?@", $this->getTable(), $_admin)
									->fetchAssocAll('modul', 'group', 'group');
	}

}

?>