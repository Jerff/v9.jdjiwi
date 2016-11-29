<?php


class photo_image_multi_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('photo_image_multi_db');

		// формы
		$form = $this->form()->get();
        $this->setNewPos();

        $form->add('name',           new cFormText(array('max'=>255, '1!empty')));

        $form->add('visible',        new cmfFormCheckbox());


        $size=array();
        $size['main'] = array(photoMainWidth, photoMainHeight);
		$size['section'] = array(photoSmallWidth, photoSmallHeight);
		$form->add('image',	new cmfFormImage(array('!empty', 'name'=>'image', 'path'=>path_photo, 'size'=>$size, 'addField', 'watermark')));
	}

	protected function saveStart(&$send) {
		if(empty($send['name']) and empty($send['image'])) {
		    $send = array();
		    return;
		}

		if(count($send) and !$this->id()->get()) {
			 $send['photo'] = cAdmin::menu()->sub()->getId();
		}
		cCommand::set('isMultiUplod');
		parent::saveStart($send);
	}

}

?>