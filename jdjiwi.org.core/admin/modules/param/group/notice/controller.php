<?php


class param_group_notice_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_group_notice_modul');

		// url
		$this->url()->setSubmit('/admin/param/group/notice/');
		$this->url()->set('select', '/admin/param/group/notice/');
	}

	public function getSelectUrl($id) {
		$opt = array('parentId'=>$id);
		return $this->url()->get('select', $opt);
	}

	public function deleteSection($id) {
		$where = array('group'=>$id);
		$this->delete($this->getListId($where));
	}

	public function parentParam() {
		$parent = cAdmin::menu()->sub()->getId();
		list($name, $path) = cLoader::initModul('catalog_section_edit_db')->getFeildsRecord(array('name', 'path'), $parent);
		$path1 = $path2 = cConvert::pathToArray($path);
		$path1[$parent] = $parent;
		$res = $this->sql()->placeholder("SELECT p.param FROM ?t p WHERE p.`group` ?@ ORDER BY FIELD(`group`, ?s)", db_param_group_select, $path1, implode(',', $path1))
								->fetchRowAll(0, 0);
		$menu = model_param::initParamMenu();
        $filter = array();
        foreach($res as $p) {
            $filter[$p] = get($menu, $p);
        }

		if($path2) {
			$res = $this->sql()->placeholder("SELECT p.`group`, (SELECT s.name FROM ?t s WHERE s.id=p.group) AS name, (SELECT name FROM ?t WHERE id IN (SELECT sec.basket FROM ?t sec WHERE sec.id=p.group)) AS basket, p.param FROM ?t p WHERE p.`group` ?@ ORDER BY FIELD(`group`, ?s), p.pos", db_section, db_param, db_section, db_param_group_notice, $path2, implode(',', $path2))
									->fetchAssocAll();
			$menu = model_param::initParamMenu();
			$param = array();
			foreach($res as $row) {
			    $param[$row['group']]['basket'] = $row['basket'];
	            $param[$row['group']]['name'] = $row['name'];
	            $param[$row['group']]['list'][$row['param']] = get($menu, $row['param']);
	            unset($filter[$row['param']]);
			}
		} else {
			$param = false;
		}

		return array($name, $filter, $param);
	}

}

?>