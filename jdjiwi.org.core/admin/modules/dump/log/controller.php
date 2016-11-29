<?php


class dump_log_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'dump_log_modul');

		// url
		$this->url()->setSubmit('/admin/dump/log/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/news/', $arg->uri);
    }

	public function delete($id) {
		$id = cLoader::initModul('news_edit_controller')->delete($id);
		return parent::delete($id);
	}

}

?>