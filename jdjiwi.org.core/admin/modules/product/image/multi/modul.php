<?php


class product_image_multi_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('product_image_multi_db');

		// формы
		$form = $this->form()->get();
        $this->setNewPos();

        $form->add('name',           new cFormText(array('max'=>255, '1!empty')));
        $form->add('color',          new cmfFormSelectCheckbox());
        $form->add('visible',        new cmfFormCheckbox());


        $size=array();
        $size['main'] = array(imageMainWidth, imageMainHeight);
        $size['section'] = array(imageSectionWidth, imageSectionHeight);
        $size['product'] = array(imageProductWidth, imageProductHeight);
		$size['small'] = array(imageSmallWidth, imageSmallHeight);
		$form->add('image',	new cmfFormImage(array('!empty', 'name'=>'image', 'path'=>path_product, 'size'=>$size, 'addField', 'watermark')));
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

	protected function saveStart(&$send) {
		if(empty($send['name']) and empty($send['color']) and empty($send['image'])) {
		    $send = array();
		    return;
		}

		if(count($send) and !$this->id()->get()) {
			 $send['product'] = cAdmin::menu()->sub()->getId();
		}
		cCommand::set('isMultiUplod');
		parent::saveStart($send);
	}

}

?>