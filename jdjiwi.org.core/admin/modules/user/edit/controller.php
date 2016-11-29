<?php


class user_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'user_edit_modul');

		// url
		$this->url()->setSubmit('/admin/user/edit/');
		$this->url()->setCatalog('/admin/user/');
	}

	public function delete($id) {

		cLoader::initModul('user_param_controller')->delete($id);
		cLoader::initModul('subscribe_mail_edit_controller')->deleteUser($id);
		return parent::delete($id);
	}

	public function getUserStat() {
		return cLoader::initModul('user_edit_db')->getUserStat($this->id()->get());
	}

	public function activate($id=0) {
		if(!$id) $id = $this->id()->get();
		$data = $this->modul()->getDb()->getDataRecord($id);
		if(get($data, 'register')!=='no') return;
        cmfUserModel::save(array('register'=>'yes', 'visible'=>'yes'), $id);
        $data['name'] = cmfUser::generateName($data);

        $cmfMail = new cmfMail();
		$cmfMail->sendTemplates('Личный кабинет: Регистрация (активация из админки)', $data, $data['email']);

		$this->ajax()->reload();
	}

}

?>