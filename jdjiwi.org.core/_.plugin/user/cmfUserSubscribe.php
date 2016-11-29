<?php

cLoader::library('ajax/cControllerAjaxSave');
cLoader::library('subscribe/cmfSubscribe');
cLoader::library('user/model/cmfUserModel');
class cmfUserSubscribe extends cControllerAjaxSave {

	function __construct($formUrl=null, $name=null, $func=null) {

		if(!$name)		$name = 'subscribe';
		if(!$formUrl)	$formUrl = cAjaxUrl .'/user/subscribe/?';
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func);
	}

	protected function init() {
		$form = $this->form()->get();
		foreach(cmfSubscribe::typeList() as $k=>$v) {
            $form->add($k,    new cmfFormCheckbox(array('label'=>$v)));
		}
	}

	public function loadData() {
		$user = cRegister::getUser();
		$isAll = true;
		foreach(cmfSubscribe::typeList() as $k=>$v) {
            if($v==='yes') {
                $this->form()->get()->select($k, $v);
                $isAll = false;
            }
		}
		if($isAll) {
            cmfSubscribe::selectForm($this->form()->get(), $user->getId(), $user->email);
		}
	}

	public function run1() {
		$userData = $this->processing();

        $user = cRegister::getUser();
		$id = $user->getId();
        cmfUserModel::saveParam($userData, $user->getId());

        foreach(cmfSubscribe::typeList() as $k=>$v) {
            if(isset($userData[$k])) {
        		if($userData[$k]==='yes') {
        		    cmfSubscribe::addUser($user->getId(), $user->email, $k);
        		} else {
                    cmfSubscribe::delUser($user->getId(), $user->email, $k);
        		}
    		}
		}

		cAjax::get()->script($this->form()->get()->jsUpdate());
		$this->runSaveOk();
	}

}

?>