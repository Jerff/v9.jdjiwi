<?php


class _pages_main_list_db extends driver_db_list_one {

	protected function getTable() {
		return db_pages_main;
	}

 	public function loadData(&$row) {
		if($row['type']==='pagesSystem') $row['type'] = 'pages';
		parent::loadData($row);
	}

	public function getSelectTree(&$element, $index=false, $isTitle=true) {
		$res = $this->sql()->placeholder("SELECT id, parent, name FROM ?t WHERE visible='yes' AND type IN('tree', 'list') ORDER BY ?o", $this->getTable(), $this->getSort());
		$menu = array();
		$menu[0]['default']['name'] = 'По умолчанию';
		$id = array();
		while($row = $res->fetchAssoc()) {
			$id[] = $row['id'];
			$menu[$row['parent']][$row['id']]['name'] = $row['name'];
		}
		$res->free();

		if($isTitle) {
			$_title=array();
			$res = $this->sql()->placeholder("SELECT uri,CONCAT(IF(LENGTH(title),'t',0),IF(LENGTH(keywords),'k',0),IF(LENGTH(description),'d',0)) FROM ?t", db_seo_title);
			while($row=$res->fetchRow())
				$_title[$row[0]] = '['. $row[1] .'] ';
			$res->free();
		}

		$res = $this->sql()->placeholder("SELECT c.id, c.parent, c.name, c.small_name  FROM ?t c WHERE c.parent ?@ AND ((c.type='pages' AND c.url!='') OR (c.type='pagesSystem' AND c.small_name='/404/')) AND c.visible='yes' ORDER BY c.pos", $this->getTable(), $id);
		$pages = array();


		$default = 'default';
		if($isTitle) {
			$name = isset($_title[$default]) ? $_title[$default] : '[000] ';
		} else {
			$name = '';
		}
		$pages[$default][$default]['name'] = $name .'По умолчанию';
		$pages[$default][$default]['small_name'] = 'default';



		$name = '';
		while($row = $res->fetchAssoc()) {
			$j = $row['id'];
			$p = $row['parent'];
			if($isTitle) {
				$name = $row['small_name'];
				$name = isset($_title[$name]) ? $_title[$name] : '[000] ';
			}
			if(!$row['name']) continue;
			$pages[$p][$j]['name'] = $name . $row['name'];
			$pages[$p][$j]['small_name'] = $row['small_name'];
		}

		$res->free();
		$this->treeSelect($element, $menu, $pages, $index);
	}

	private function treeSelect(&$element, &$menu, &$pages, $index, $parent=0, $name='') {
		foreach($menu[$parent] as $key=>$value) {
			$options = $name . (empty($name) ? $value['name'] : ' -> '. $value['name']);
			if(isset($pages[$key]))
				foreach($pages[$key] as $key2=>$value2){
					$element->addElement(array($options, $index ? $key2 : $value2['small_name']), $value2['name']);
				}
			if(isset($menu[$key])) {
				$this->treeSelect($element, $menu, $pages, $index, $key, $options);
			}
		}
	}

	public function getPagesVariables($id) {
		return $this->sql()->placeholder("SELECT variables FROM ?t WHERE small_name=? AND visible='yes' AND type='pages'", db_pages_main, $id)
								->fetchRow(0);
	}

}

?>