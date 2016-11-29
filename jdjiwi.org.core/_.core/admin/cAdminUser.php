<?php

cLoader::library('user/authorization/cmfAuth');
class cAdminUser extends cmfAuth {

	protected function getName() {
	    return 'sessionAdmin';
	}

	// читаем нужные данные
	protected function getFields() {
		return array('id', 'login', 'name', 'admin', 'param');
	}
    // читаем дополнительные данные
	public function getFieldsParam() {
		return array('id');
	}


	protected function getWhere() {
		return array("(LENGTH(`admin`)>1)");
	}

	protected function sessionUpdate() {
		$data = $this->getData();
		$group = cConvert::pathToArray($data['admin']);
		$this->set('group', $group);
		$this->set('groupString', implode(',', $group));
		parent::sessionUpdate();
	}

	// данные группы
	public function getGroup() {
		return $this->get('group');
	}
	public function getGroupString() {
		return $this->get('groupString');
	}

}

?>