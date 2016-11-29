<?php


class content_pages_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_pages_list_modul');

		// url
		$this->url()->setSubmit('/admin/content/pages/');
		$this->url()->setEdit('/admin/content/pages/edit/');

	}

	public function filterType() {
		$filter = model_content_pages::typeList();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilter($filter, 'type', 'end');
	}

	public function delete($id) {
		$id = cLoader::initModul('content_pages_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>