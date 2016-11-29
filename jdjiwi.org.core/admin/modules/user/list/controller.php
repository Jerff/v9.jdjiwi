<?php


class user_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'user_list_modul');

		// url
		$this->url()->setSubmit('/admin/user/');
		$this->url()->setEdit('/admin/user/edit/');

		$this->access()->writeAdd('userUnban|userExit|changeFilter');
	}

	public function changeFilter() {
        $name = trim(cInput::post()->get('name'));
        $email = trim(cInput::post()->get('email'));

        $opt = array();
        $opt['name'] = $name ? $name : null;
		$opt['email'] = $email ? $email : null;
		$this->ajax()->redirect($this->url()->getSubmit($opt));
	}

	public function filterGroup() {
		$filter = cLoader::initModul('user_group_list_db')->getNameList();
		$filter[0]['name'] = 'Все клиенты';
		return parent::abstractFilter($filter, 'main', 'end');
	}

	public function delete($id) {
		cmfUserModel::accesIs($id);
		$id = cLoader::initModul('user_edit_controller')->delete($id);
		return parent::delete($id);
	}

	protected function userUnban($id){
		cmfUserModel::accesIs($id);
		cmfUserModel::userUnban($id);
		$this->ajax()->script("
\$('#userUnban{$id}').hide();
\$('#userExit{$id}').show();");
	}

	protected function userExit($id){
		cmfUserModel::accesIs($id);
		cmfUserModel::userExit($id);
	}

	public function activate($id) {
		cLoader::initModul('user_edit_controller')->activate($id);
	}

}

?>