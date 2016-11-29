<?php


class subscribe_history_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'subscribe_history_list_modul');

		// url
		$this->url()->setSubmit('/admin/subscribe/history/');
		$this->url()->setEdit('/admin/subscribe/history/view/');
        $this->access()->removeDel('delete');
	}

	public function getEditUrl($opt=null) {
		return $this->url()->get('edit', $opt);
	}

	public function delete($id) {
		$id = cLoader::initModul('subscribe_history_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>