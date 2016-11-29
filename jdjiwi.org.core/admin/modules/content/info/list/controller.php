<?php


class content_info_list_controller extends driver_controller_list_tree {

	protected function init() {
		parent::init();
		$this->initModul('main',	'content_info_list_modul');

		// url
		$this->url()->setSubmit('/admin/content/info/');
		$this->url()->setEdit('/admin/content/info/edit/');

	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/info/', $arg->isUri);
    }

	public function delete($id) {
		$id = cLoader::initModul('content_info_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function copy($id) {
		$this->copyInit();
		cLoader::initModul('content_info_edit_controller')->copy($id);
		$this->copyInit();
	}

}

?>