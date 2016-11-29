<?php


class showcase_edit_image_modul extends driver_modul_list {

    function __construct($id=null) {
        $this->setIdName('image');
        parent::__construct($id);
	}

	protected function init() {
		parent::init();

		$this->setDb('showcase_edit_image_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

        $form->add('name',          new cFormText(array('max'=>100)));
        $form->add('type',		    new cmfFormRadio(array('!empty')));
		$form->add('visible',		new cmfFormCheckbox());

		$form->add('url',			new cFormText(array('max'=>250)));

		$form->add('section',		new cmfFormSelectInt());
		$form->add('brand',		    new cmfFormSelectInt());
		$form->add('product',		new cmfFormSelectInt());

		$form->add('top',		    new cmfFormTextInt());
		$form->add('left',		    new cmfFormTextInt());
		$form->add('height',		new cmfFormTextInt());
		$form->add('width',		    new cmfFormTextInt());
	}

	protected function updateIsErrorData($data, &$isError) {
		if(!$this->id()->get()) {
			if(!$this->getFilter('id')) {
				$isError = true;
				$this->form()->get()->setError('name', 'Выберите сначало подраздел');
			} else {
				if(!cLoader::initModul('showcase_edit_db')->getFeildRecord('id', $this->getFilter('id'))) {
					$isError = true;
					$this->form()->get()->setError('name', 'Раздел выбрать нельзя');
				}
			}
		}
		return $isError;
	}

    public function loadForm() {
        $form = $this->form()->get();
        $form->addElement('type', 'product', 'Товар');
        $form->addElement('type', 'edit', 'Заполнить в ручную');
        $form->select('type', 'product');

        $section = cRegister::sql()->getQuery("(id=? OR path LIKE '%[?i]%')", cAdmin::menu()->sub()->getId(), cAdmin::menu()->sub()->getId());
        $name = cLoader::initModul('catalog_section_list_tree')->getNameList();

        if($name) {
            cGlobal::set('$sectionId', key($name));
        }
        $form->addElement('section', 0, 'Не выбрано');
        foreach($name as $key=>$value)
            $form->addElement('section', $key, $value['name']);

        $name = cLoader::initModul('catalog_brand_list_db')->getNameList();
        cGlobal::set('$brandId', key($name));
        $form->addElement('brand', 0, 'Не выбрано (ccылка на раздел)');
        //$form->addElement('brand', -1, 'ссылка на раздел');
        foreach($name as $key=>$value)
            $form->addElement('brand', $key, $value['name']);
    }

    public function loadForm2($id=null) {
        if(!$id) $id = $this->id()->get();

        $form = $this->form()->get();
        $data = $this->getDataId($id);

        $section = get($data, 'section');
        $brand = get($data, 'brand');
        if(!$section) $section = cGlobal::get('$sectionId');

        $section = cRegister::sql()->placeholder("SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%'", db_section, $section, $section)
                                        ->fetchRowAll(0, 0);
        $name = cLoader::initModul('product_list_db')->getProductList($section, array(1), array('price'));
        $product = get($data, 'product');

        $form->resetElement('product');
        $form->addElement('product', 0, 'Не выбрано (ccылка на раздел)');
        //$form->addElement('product', -1, 'ссылка на раздел');
        foreach($name as $key=>$value) {
            $form->addElement('product', $key, $value['name']);
        }
        $form->select($data);
        return get($name, $product);
    }


    public function newLine($index, &$data) {
        $data['type'] = 'edit';
        return parent::newLine($index, $data);
    }

    public function onchangeSection($id) {
        $form = $this->form()->get();
        $form->changeName($this->getNameID($id));
        $section = $form->handlerElement('section');
        $brand = $form->handlerElement('brand');
        $product = $form->handlerElement('product');

        if(!$section) {
            $section = cRegister::sql()->placeholder("SELECT id FROM ?t", db_section)
                                        ->fetchRowAll(0, 0);
        } else {
            $section = cRegister::sql()->placeholder("SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%'", db_section, $section, $section)
                                        ->fetchRowAll(0, 0);
        }

        $name = cLoader::initModul('product_list_db')->getProductList($section, $brand ? array('brand'=>$brand): array(1), array('price'));
        $js = $this->getJsProductData($id, get($name, $product));

        $form->resetElement('product');
        $form->addElement('product', 0, 'Не выбрано (ccылка на раздел)');
        //$form->addElement('product', -1, 'ссылка на раздел');
        foreach($name as $key=>$value)
            $form->addElement('product', $key, $value['name']);
		$form->select('product', $product);
		$js .= $form->get('product')->jsUpdate();
		$this->ajax()->script($js);
	}

	public function onchangeProduct($id) {
		$form = $this->form()->get();
		$form->changeName($this->getNameID($id));
		$section = $form->handlerElement('section');
		$brand = $form->handlerElement('brand');
		$product = $form->handlerElement('product');

		if(!$section) {
            $section = cRegister::sql()->placeholder("SELECT id FROM ?t", db_section)
										->fetchRowAll(0, 0);
		} else {
			$section = cRegister::sql()->placeholder("SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%'", db_section, $section, $section)
										->fetchRowAll(0, 0);
		}

		$name = cLoader::initModul('product_list_db')->getProductList($section, $brand ? array('brand'=>$brand): array(1), array('price'));
		if(isset($name[$product])) {
			$this->ajax()->script($this->getJsProductData($id, $name[$product]));
		}
	}

	protected function getJsProductData($id, $value=null) {
		$name = cJScript::quote(get($value, 'name'));
		return <<<HTML
\$('#name{$id}').html('$name');
HTML;
	}

	protected function saveStart(&$send) {
		$send['parent'] = (int)$this->getFilter('id');
		parent::saveStart($send);
		if(isset($send['width']) and empty($send['width'])) {
            $send['width'] = 50;
		}
		if(isset($send['height']) and empty($send['height'])) {
            $send['height'] = 50;
		}
		$this->command()->reloadView();
	}

}

?>