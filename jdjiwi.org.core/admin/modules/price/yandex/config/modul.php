<?php


class price_yandex_config_modul extends driver_modul_edit_param_of_record {


	protected function init() {
		parent::init();

		$this->setDb('price_yandex_config_db');

		// формы
		$form = $this->form()->get();
		$form->add('name',	    new cmfFormTextarea(array('!empty', 'max'=>255)));
		$form->add('company',	new cmfFormTextarea(array('!empty', 'max'=>255)));
		$form->add('price',	    new cmfFormTextInt());
		$form->add('product',	new cmfFormSelect(array('max'=>255)));
//		$form->add('delivery',	new cmfFormSelect(array('max'=>255)));
		$form->add('notice',	new cmfFormSelect(array('max'=>255)));
	}

	public function loadForm() {
		parent::loadForm();

		$form = $this->form()->get();
//		$form->addElement('delivery', 'true', 	'Есть');
//		$form->addElement('delivery', 'false',  'Нет');

		$form->addElement('product', 'name', 	'Название товара');
		$form->addElement('product', 'section', 'Название раздела + название товара');
		$form->addElement('product', 'path', 	'путь к категории товара + название товара');

		$form->addElement('notice', 'none', 'Не экспортировать');
		$form->addElement('notice', 'param', 'Экспортировать краткое описание параметров');
		$form->addElement('notice', 'content', 'Экспортировать полное описание');
		$form->addElement('notice', 'param+content', 'Экспортировать краткое описание параметров + полное описание товара');
	}

}

?>