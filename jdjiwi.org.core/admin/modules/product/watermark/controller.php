<?php

class product_watermark_controller extends driver_controller_edit_param_of_record {

    protected function init() {
        parent::init();
        $this->initModul('main', 'product_watermark_modul');

        // url
        $this->url()->setSubmit(cPages::getMain());
    }

}

class product_watermark_modul extends driver_modul_edit_param_of_record {


	protected function init() {
		parent::init();

		$this->setDb('product_watermark_db');

		// формы
		$form = $this->form()->get();
		$form->add('enable',    new cmfFormCheckbox());
		$form->add('type',	    new cmfFormRadio());
		$form->add('place',	    new cmfFormRadio());
		$form->add('notice',	new cFormText(array('max'=>255)));
		$form->add('size',	    new cFormText(array('max'=>255)));
		$form->add('image',	    new cmfFormFile(array('name'=>'image', 'path'=>path_watermark)));
	}

	public function loadForm() {
		parent::loadForm();

		$form = $this->form()->get();
		$form->addElement('type', 'file', 	'Использовать файл');
		$form->addElement('type', 'text',   'Использовать текст');

		$form->addElement('place', 'NorthWest', 	'Верхний левый угол');
		$form->addElement('place', 'SouthWest',   'Нижний левый угол');
		$form->addElement('place', 'NorthEast', 	'Верхний правый угол');
		$form->addElement('place', 'SouthEast',   'Нижний правый угол');
	}

	protected function saveStart(&$send) {

		if(isset($send['type']) or isset($send['place']) or isset($send['size']) or isset($send['notice'])) {
            $type = get($send, 'type', $this->form()->get()->handlerElement('type'));
            $size = get($send, 'size', $this->form()->get()->handlerElement('size'));
            $notice = get($send, 'notice', $this->form()->get()->handlerElement('notice'));
            if($type==='text') {
                cImage::createLogo($size, $notice);
            }
		}
		parent::saveStart($send);
	}

}


class product_watermark_db extends driver_db_edit_param_of_record {

}

?>