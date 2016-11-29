<?php


class product_image_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('product_image_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('name',		    new cFormText(array('max'=>255, '1!empty')));
		$form->add('color',		    new cmfFormSelectCheckbox());
		$form->add('visible',		new cmfFormCheckbox());


        $size=array();
        $size['main'] = array(imageMainWidth, imageMainHeight);
		$size['section'] = array(imageSectionWidth, imageSectionHeight);
		$size['product'] = array(imageProductWidth, imageProductHeight);
		$size['small'] = array(imageSmallWidth, imageSmallHeight);
		$form->add('image',	new cmfFormImage(array('name'=>'image', 'path'=>path_product, 'size'=>$size, 'addField', 'watermark')));

		$this->setGalleryPath(path_product);
		$this->setGallerySize(imageSectionWidth, imageSectionHeight);
	}

    public function loadForm() {
        parent::loadForm();

        $form = $this->form()->get();
        $name = cLoader::initModul('param_color_list_db')->getNameList();
		foreach($name as $k=>$v) {
            $form->addElement('color', $k, $v['name']);
            $form->select('color', $k);
        }
	}

	public function getGalleryId() {
	    return 'image_section';
	}

	protected function saveStart(&$send) {
		if(count($send) and !$this->id()->get()) {
			 $send['product'] = cAdmin::menu()->sub()->getId();
		}
		parent::saveStart($send);
	}

}

?>