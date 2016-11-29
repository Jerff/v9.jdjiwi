<?php


class content_content_list_controller extends driver_controller_list_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_content_list_modul');

		// url
		$this->url()->setSubmit('/admin/content/content/');
		$this->url()->setEdit('/admin/content/content/edit/');

	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/content/', $arg->isUri);
    }

	public function delete($id) {
		$id = cLoader::initModul('content_content_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function copy($id) {
		$this->copyInit();
		cLoader::initModul('content_content_edit_controller')->copy($id);
		$this->copyInit();
	}

}

?>