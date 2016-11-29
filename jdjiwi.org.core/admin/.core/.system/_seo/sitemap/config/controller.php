<?php

class _seo_sitemap_config_controller extends driver_controller_edit_param_of_record {

    protected function init() {
        parent::init();
        $this->initModul('main', '_seo_sitemap_config_modul');

		// url
		$this->url()->setSubmit(cPages::getMain());
    }

}


class _seo_sitemap_config_modul extends driver_modul_edit_param_of_record {


    protected function init() {
        parent::init();

        $this->setDb('_seo_sitemap_config_db');

        // формы
        $form = $this->form()->get();
    }

    public function loadForm() {
        $form = $this->form()->get();
	}

}


class _seo_sitemap_config_db extends driver_db_edit_param_of_record {

}

?>