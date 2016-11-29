<?php


class subscribe_history_view_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'subscribe_history_view_modul');
	}

    public function deleteSubscribe($id) {
		$old = cAdmin::menu()->sub()->getId();
		cAdmin::menu()->sub()->setId($id);
		$this->delete($this->getListId(array('subscribe'=>$id)));
		cAdmin::menu()->sub()->setId($old);
	}

}

?>