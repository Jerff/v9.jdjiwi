<?php


class cmfFormPassword extends cFormText {
	private $confirmName = null;

	private static $error = array();
	private static $confirm = array();
	private static $confirmStart = null;

    function __construct($o=null) {
        parent::__construct($o);

        if(isset($o['sha1']))            $this->setReform('sha1');
		if(isset($o['confirmName']))	$this->setConfirmName($o['confirmName']);
		$this->setFilter('cmfFilterLenMin', 4);
		$this->setFilter('cmfFilterLenMax', 40);

		$this->setType('password');
	}


	protected function init1($o) {

		if(isset($o['sha1']))			$this->setReform('sha1');
		if(isset($o['confirmName']))	$this->setConfirmName($o['confirmName']);
		$this->setFilter('cmfFilterLenMin', 4);
		$this->setFilter('cmfFilterLenMax', 40);

		$this->setType('password');
		parent::init($o);
	}

	private function setConfirmName($name) {
		$this->confirmName = $name;
	}
	private function getConfirmName() {
		return $this->confirmName;
	}

	public function reset() {
		parent::reset();
		unset(self::$confirm[$this->getConfirmName()]);
		self::$error = null;
	}

	public function select($value, $data=null) {
		return null;
	}
	public function getValueOld() {
		return null;
	}
	public function getValue() {
		return null;
	}

	public function processing($data, $old, $upload) {
		$ConfirmName = $this->getConfirmName();

		$value = get($data, $this->getId());
		foreach($this->getFilter() as $filter=>$opt) {
			if(!$filter($value, $opt)) {
				$this->select($value);
				return null;
			}
		}
		if(!empty($value)) {
			foreach($this->getReform() as $reform=>$opt) {
				if(is_array($opt)) $value = $reform($value, $opt['out'], $opt['in']);
				else $value = $reform($value, $opt);
			}
		}

		if(isset(self::$confirm[$ConfirmName])) {
			if((self::$confirm[$ConfirmName]!==$value)) {
				cmfFormError::set("Неправильное подтверждение пароля");
			}
			return null;
		} else {
			self::$confirm[$ConfirmName] = $value;
			self::$confirmStart = $this->getId();
		}
		return $value ? $value : null;
	}

	protected function jsUpdateValue() {
        return '';
	}

    public function jsUpdateOld() {
        return parent::jsUpdateValue() . parent::jsUpdateOld();
    }

}

class cmfFormPasswordView extends cmfFormPassword {

    function __construct($o=null) {
        parent::__construct($o);

		$this->setType('text');
	}

}

?>
