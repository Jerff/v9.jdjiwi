<?php


class price_yandex_config_controller extends driver_controller_edit_param_of_record {

	protected function init() {
		parent::init();
		$this->initModul('main',		'price_yandex_config_modul');
		$this->initModul('section',	'price_yandex_section_modul');

		// url
		$this->url()->setSubmit('/admin/price/yandex/');

		$this->access()->writeAdd('updateYandex');
	}

	protected function updateYandex() {
        cmfCronRun::runModul('Yandex.Market');
        $this->ajax()->alert('Обновление завершено');
	}

}

?>