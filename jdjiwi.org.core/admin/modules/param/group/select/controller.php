<?php


class param_group_select_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_group_select_modul');

		// url
		$this->url()->setSubmit('/admin/param/group/select/');
		$this->url()->set('select', '/admin/param/group/select/');
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
		list($name, $path) = cLoader::initModul('catalog_section_edit_db')->getFeildsRecord(array('name', 'path'), cAdmin::menu()->sub()->getId());
		if($path) {
			$path = cConvert::pathToArray($path);
			$res = $this->sql()->placeholder("SELECT p.`group`, (SELECT s.name FROM ?t s WHERE s.id=p.group) AS name, p.param FROM ?t p WHERE p.`group` ?@ ORDER BY FIELD(`group`, ?s), p.pos", db_section, db_param_group_select, $path, implode(',', $path))
									->fetchAssocAll();
			$menu = model_param::initParamMenu();
			$param = array();
			foreach($res as $row) {
			    $param[$row['group']]['name'] = $row['name'];
	            $param[$row['group']]['list'][] = get($menu, $row['param']);
			}
		} else {
			$param = false;
		}
		return array($name, $param);
	}

}

?>