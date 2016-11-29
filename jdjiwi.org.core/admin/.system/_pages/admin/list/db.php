<?php


class _pages_admin_list_db extends driver_db_list_one {

	protected function getTable() {
		return db_pages_admin;
	}


	public function getSelectGroup(&$select) {
		$menu = $this->sql()->placeholder("SELECT p.`id`, p.`parent`, p.`name`, p.`type`, count(s.id) AS count FROM ?t p LEFT JOIN ?t s ON(p.id=s.parent AND s.type='pages') WHERE p.visible='yes' AND p.type IN('tree', 'list') GROUP BY p.id ORDER BY ?o:p", $this->getTable(), $this->getTable(), $this->getSort())
									->fetchAssocAll('parent', 'id');
		$this->treeSelectGroup($select, $menu);
	}


	private function treeSelectGroup(&$select, &$menu, $parent=0, $name='') {
		foreach($menu[$parent] as $key=>$value) {
			$options = $name . (empty($name) ? $value['name'] : ' -> '. $value['name']);

			if(isset($menu[$key])) {
				foreach($menu[$key] as $key2=>$value2){
					if($value2['count']) $select->addElement(array($options, $key2), $value2['name']);
				}
				if($value['type']==='tree') $this->treeSelectGroup($select, $menu, $key, $options);
			}
		}
	}

}

?>