<?php


abstract class driver_db_edit_param_of_record extends driver_db_edit {

    function __construct() {
		$this->setIdName('config');
		parent::__construct();
	}

    protected function init() {
        parent::init();
        $this->command()->ignore()->noRecord();
    }

    public function constructOld() {
		parent::__construct();
	}

	protected function getTable() {
		return db_sys_settings;
	}

	public function getRecordId() {
		return 'data';
	}

	protected function getWhereId($id) {
		return array('id'=>$id, 'AND', '1');
	}

	public function save($send) {
		$data = $this->runData();
		if($data) {
			foreach($send as $k=>$v)
				$data[$k] = $v;
			$send = $data;
		}
		$this->sql()->add($this->getTable(), array($this->getRecordId()=>serialize($send)), $this->getWhere());
		$this->update()->set($this->id()->get(), $send);
	}

	protected function loadSetData(&$data) {
		if(!empty($data[$this->getRecordId()])) {
             $data = unserialize($data[$this->getRecordId()]);
		} else {
			 $data = array();
		}
		return $data;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('config');
	}

}

?>