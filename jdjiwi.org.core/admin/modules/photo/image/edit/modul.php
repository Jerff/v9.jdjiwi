<?php


class photo_image_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('photo_image_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('name',		    new cFormText(array('max'=>255, '1!empty')));
		$form->add('visible',		new cmfFormCheckbox());


        $size=array();
        $size['main'] = array(photoMainWidth, photoMainHeight);
		$size['section'] = array(photoSmallWidth, photoSmallHeight);
		$form->add('image',	new cmfFormImage(array('name'=>'image', 'path'=>path_photo, 'size'=>$size, 'addField', 'watermark')));

		$this->setGalleryPath(path_product);
		$this->setGallerySize(imageSectionWidth, imageSectionHeight);
	}

	public function getGalleryId() {
	    return 'image_section';
	}

	protected function saveStart(&$send) {
		if(count($send) and !$this->id()->get()) {
			 $send['photo'] = cAdmin::menu()->sub()->getId();
		}
		parent::saveStart($send);
	}

}

?>