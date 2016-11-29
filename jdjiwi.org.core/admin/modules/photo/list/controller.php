<?php


class photo_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'photo_list_modul');

		// url
		$this->url()->setSubmit('/admin/photo/');
		$this->url()->setEdit('/admin/photo/edit/');
		$this->url()->set('attach', '/admin/photo/attach/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/photo/', $arg->uri);
    }

	public function delete($id) {
		$id = cLoader::initModul('photo_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>