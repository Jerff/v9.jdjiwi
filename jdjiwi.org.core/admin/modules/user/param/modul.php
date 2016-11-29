<?php


class user_param_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('user_param_db');

		// формы
		$form = $this->form()->get();
		$form->add('cod',           new cFormText(array('errorHide1', 'specialchars', 'max'=>250)));
		$form->add('phone',		    new cFormText(array('errorHide1', 'name'=>'Телефон', 'specialchars', 'max'=>250)));
        $form->add('gorod',     new cFormText(array('name'=>'Город', 'max'=>100)));
        $form->add('index',     new cFormText(array('name'=>'Индекс', 'max'=>15)));

		foreach(model_subscribe::typeList() as $k=>$v) {
            $form->add($k,    new cmfFormCheckbox(array('label'=>$v['name'])));
		}
	}

	public function loadForm() {
		parent::loadForm();

		$user = cLoader::initModul('user_edit_db')->getDataRecord($this->id()->get());
		cGlobal::set('$userName', cmfUser::generateName($user));
	}

	protected function selectForm($data) {
		parent::selectForm($data);
		$user = cLoader::initModul('user_edit_db')->getDataRecord($this->id()->get());

        $isAll = true;
        foreach(model_subscribe::typeList() as $k=>$v) {
            if(get($data, $k)==='yes') {
                $isAll = false;
            }
		}
		if($isAll) {
            cmfSubscribe::selectForm($this->form()->get(), $this->id()->get(), $user['email']);
		}
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(!$this->id()->get()) {
			$send['date'] = date('Y-m-d H:i:s');
			$send['status'] = 'неактивна';
		}
	}

	protected function saveEnd($send) {
	    $user = cLoader::initModul('user_edit_db')->getDataRecord($this->id()->get());
	    foreach(model_subscribe::typeList() as $k=>$v) {
            if(isset($send[$k])) {
        		if($send[$k]==='yes') {
        		    cmfSubscribe::addUser($this->id()->get(), $user['email'], $k);
        		} else {
                    cmfSubscribe::delUser($this->id()->get(), $user['email'], $k);
        		}
    		}
		}
	}

}

?>