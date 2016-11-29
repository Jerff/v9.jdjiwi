<?php


class _pages_main_edit_db extends driver_db_edit_tree {

	protected function getTable() {
		return db_pages_main;
	}

	protected function getFilterParent2() {
		return $this->getFilter('new_parent');
	}


	public function getTypeSmallName($parent) {
		return $this->sql()->placeholder("SELECT small_name, type FROM ?t WHERE id=?", $this->getTable(), $parent)
									->fetchRow();
	}

	public function getTypeSmallNameOld($id) {
		if($id) {
			list($parent, $type) = $this->sql()->placeholder("SELECT parent, type FROM ?t WHERE id=?", $this->getTable(), $id)
											->fetchRow();
			if($parent) {
				list($smallName) = $this->sql()->placeholder("SELECT small_name FROM ?t WHERE id=?", $this->getTable(), $parent)
											->fetchRow();
			} else {
				$small_name = '';
			}
		} else {
			$parent = $this->getFilter('parent');
			if($parent) {
				list($smallName, $type) = $this->sql()->placeholder("SELECT small_name, type, admin FROM ?t WHERE id=?", $this->getTable(), $parent)
													->fetchRow();
				if($type==='list') {
					$type='pages';
				}
			} else {
				$type = 'tree';
				$smallName = '';
			}
		}
		return array($parent, $type, $smallName);
	}

	public function loadData(&$row) {
		parent::loadData($row);
		cGlobal::set('pageType', get($row, 'type'));
	}

	protected function loadFormFilterParent() {
		return array("`type` IN('tree', 'list')");
	}

}

?>