<?php


class photo_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('photo_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('date',		new cmfFormTextDateTime());

		$form->add('uri',			new cFormText(array('uri'=>95)));
		$form->add('header',		new cmfFormTextarea(array('max'=>255, '!empty')));
        $size = array(photoSectionWidth, photoSectionHeight);
		$form->add('image',	        new cmfFormFile(array('path'=>path_photo, 'size'=>$size, 'watermark')));
		$form->add('image_title',	new cFormText(array('max'=>255)));
		$form->add('notice',		new cmfFormTextareaWysiwyng('photo', $this->id()->get()));
		$form->add('content',		new cmfFormTextareaWysiwyng('photo', $this->id()->get()));
		$form->add('visible',		new cmfFormCheckbox());

		$form->add('title',			new cmfFormTextarea());
		$form->add('keywords',		new cmfFormTextarea());
		$form->add('description',		new cmfFormTextarea());
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		parent::saveStartUri($send, 'header', 95);
	}

}

?>