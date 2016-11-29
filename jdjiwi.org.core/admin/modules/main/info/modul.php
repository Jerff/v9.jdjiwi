<?php


class main_info_modul extends driver_modul_edit {

	const form = 'isContact|isContent|isTitle|isCoogleMap|isYandexMap';

	protected function init() {
		parent::init();

		$this->setDb('main_info_db');

		// формы
		$form = $this->form()->get();

		$isForm = array();
		foreach(explode('|', self::form) as $k) {
			$isForm[$k] = false;
		}
		switch($this->id()->get()) {
			case 'main':
				$isForm['isContent'] = true;
				break;

			case 'contact':
				//$isForm['isContact'] = true;
				$isForm['isContent'] = true;
				$isForm['isTitle'] = true;
				break;

			case 'contact/main':
				//$isForm['isContact'] = true;
				$isForm['isYandexMap'] = true;
				break;
		}

		if($isForm['isContact']) {
			$form->add('time',	new cFormText(array('max'=>255, '!empty')));
			$form->add('phone',	new cFormText(array('max'=>255, '!empty')));
		}

		if($isForm['isContent']) {
			$form->add('header',	new cFormText(array('max'=>255, '!empty')));
			$form->add('content',	new cmfFormTextareaWysiwyng('main', $this->id()->get()));
		}

		if($isForm['isCoogleMap']) {
			$form->add('mapGoogleApi',			new cmfFormTextarea(array('max'=>10000)));
			$form->add('mapGoogleCoordinates',	new cFormText(array('max'=>255)));
			$form->add('mapGoogleContent',		new cmfFormTextarea(array('max'=>255)));
		}

		if($isForm['isYandexMap']) {
			$form->add('mapYandexApi',			new cmfFormTextarea(array('max'=>10000)));
			$form->add('mapYandexCoordinates',	new cFormText(array('max'=>255)));
			$form->add('mapYandexNotice',		new cmfFormTextarea(array('max'=>255)));
			$form->add('mapYandexContent',		new cmfFormTextarea(array('max'=>10000)));
		}

		if($isForm['isTitle']) {
    		$form->add('title',			new cmfFormTextarea(array('max'=>500)));
    		$form->add('keywords',		new cmfFormTextarea(array('max'=>500)));
    		$form->add('description',	new cmfFormTextarea(array('max'=>500)));
		}
		cGlobal::set('$isForm', $isForm);
	}

}

?>