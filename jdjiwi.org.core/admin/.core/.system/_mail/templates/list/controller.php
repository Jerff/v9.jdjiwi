<?php


class _mail_templates_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_mail_templates_list_modul');

		// url
		$this->url()->setSubmit('/admin/mail/templates/');
		$this->url()->setEdit('/admin/mail/templates/edit/');

	}

	public function filterSection() {
		$filter = $this->modul()->getDb()->filterSection();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilter($filter, 'section', 'end');
	}

	public function delete($id) {
		$id = cLoader::initModul('_mail_templates_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>