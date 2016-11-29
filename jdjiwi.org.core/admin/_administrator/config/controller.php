<?php


class _administrator_config_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_administrator_config_modul');

		// url
		$this->url()->setSubmit('/admin/administrator/config/');

		$this->access()->writeDel('delete');
	}

	public function filterGroup() {
		$filter = cLoader::initModul('_administrator_group_list_db')->getNameList();
		$filter[-1]['name'] = 'Не администраторы';
		$filter[0]['name'] = 'Все администраторы';
		return parent::abstractFilter($filter, 'admin', 'end');
	}

	protected function updateAccess($list) {
		foreach($list as $id) {
			cmfModelAdmin::accesIs($id);
		}
	}

}

?>