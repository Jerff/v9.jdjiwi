<?php


class _pages_admin_edit_db extends driver_db_edit_tree {

	protected function getTable() {
		return db_pages_admin;
	}

	protected function getFilterParent2() {
		return $this->getFilter('new_parent');
	}


	public function getType($parent) {
		return $this->sql()->placeholder("SELECT type FROM ?t WHERE id=?", $this->getTable(), $parent)
									->fetchRow(0);
	}

/*	public function getTypeOld($id) {
		$sql = $this->getSql();
		if($id) {
			list($parent, $type) = $sql->placeholder("SELECT parent, type FROM ?t WHERE id=?", $this->getTable(), $id)
											->fetchRow();
		} else {
			$parent = $this->getFilter('parent');
			if($parent) {
				$type = $sql->placeholder("SELECT type, admin FROM ?t WHERE id=?", $this->getTable(), $parent)
													->fetchRow();
				if($type==='tree') {
					$type='list';
				}
			} else {
				$type = 'tree';
			}
		}
		return array($parent, $type);
	}*/

	public function loadData(&$row) {
		parent::loadData($row);
		cGlobal::set('pageType', get($row, 'type'));
	}

	protected function loadFormFilterParent() {
		return array("`type` IN('tree', 'list')");
	}

}

?>