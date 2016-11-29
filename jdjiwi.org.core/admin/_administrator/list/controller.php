<?php


class _administrator_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_administrator_list_modul');

		// url
		$this->url()->setSubmit('/admin/administrator/');
		$this->url()->setEdit('/admin/administrator/edit/');

		$this->access()->writeAdd('userUnban|userExit');
	}

	public function filterGroup() {
		$filter = cLoader::initModul('_administrator_group_list_db')->getNameList();
		$filter[-1]['name'] = 'Не администраторы';
		$filter[0]['name'] = 'Все администраторы';
		return parent::abstractFilter($filter, 'admin', 'end');
	}

	protected function updateAccess($list) {
		foreach($list as $id) {
			cmfAdminModel::accesIs($id);
		}
	}

	public function delete($id) {
		cmfAdminModel::accesIs($id);
		$id = cLoader::initModul('_administrator_edit_controller')->delete($id);
		return parent::delete($id);
	}

	protected function userUnban($id){
		cmfAdminModel::accesIs($id);
		cmfAdminModel::userUnban($id);
		$this->ajax()->script("
\$('#userUnban{$id}').hide();
\$('#userExit{$id}').show();");
	}

	protected function userExit($id){
		cmfAdminModel::accesIs($id);
		if($id==cRegister::adminId()) {
			$this->ajax()->script("cmfExit();");
		} else {
			cmfAdminModel::userExit($id);
		}
	}

}

?>