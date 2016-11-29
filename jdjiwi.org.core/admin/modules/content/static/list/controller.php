<?php


class content_static_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_static_list_modul');

		// url
		$this->url()->setSubmit('/admin/content/static/');
		$this->url()->setEdit('/admin/content/static/edit/');

	}

	public function filterSection() {
		$filter = $this->modul()->getDb()->filterSection();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilter($filter, 'section', 'end');
	}

	public function delete($id) {
		$id = cLoader::initModul('content_static_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>