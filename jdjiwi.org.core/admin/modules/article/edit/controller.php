<?php


class article_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'article_edit_modul');

		// url
		$this->url()->setSubmit('/admin/article/edit/');
		$this->url()->setCatalog('/admin/article/');

        $this->siteUrl()->init(function($data) {
                    return array('/article/', $data->uri);
                });
	}

	public function delete($id) {
		$modul = cLoader::initModul('article_attach_product_db');
		$modul->deleteAttach($id);

		return parent::delete($id);
	}

}

?>