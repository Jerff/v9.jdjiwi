<?php


class cmfFormCheckbox extends cFormElement {

	private $no = 'no';
	private $yes = 'yes';

	private $label = null;



	protected function init($o) {
		// устанавливаем опции
		if(isset($o['yes']))	$this->setYes($o['yes']);
		if(isset($o['no']))		$this->setNo($o['no']);

		if(isset($o['label']))	$this->setLabel($o['label']);

		parent::init($o);
	}

    public function getTypeElement() {
        return 'checkbox';
    }

	public function setYes($yes) {
		$this->yes = $yes;
	}
	public function getYes() {
		return $this->yes;
	}


	public function setNo($no) {
		$this->no = $no;
	}
	protected function getNo() {
		return $this->no;
	}


	public function setLabel($label) {
		$this->label = $label;
	}
	protected function getLabel() {
		return $this->label;
	}


	public function select($value, $data=null) {
		if($value === $this->getYes() or
		    $value === $this->getNo()) parent::select($value);
	}

    public function label($value) {
        $this->setLabel($value);
    }
	public function html($param, $style='') {
		$select = $this->getValue()===$this->getYes() ? 'checked' : '';

		$label = $this->getLabel();
		$input = "<input type=\"checkbox\" name=\"". ($name=$this->getId()) ."\" id=\"{$name}\" {$style} {$select} />";
		if($param==='checkbox' or !$label) {
			return $input;
		} elseif($param==='label') {
            return "<label for=\"{$name}\">&nbsp;{$label}&nbsp;</label>";
		}
		return "<label>{$input}&nbsp;{$label}&nbsp;</label>";
	}


	public function processing($data, $old, $upload) {
		$yes = $this->getYes();
		$no = $this->getNo();
		$value = empty($data[$this->getId()]) ? $no : $yes;

		// проверяем изменились ли данные?
		if($this->getOld() and $old) {
			$valueOld = get($data, $this->getOldId());
			if($value===$valueOld) return null;
        }
		$this->select($value);
		return $value;
	}

	public function jsUpdate() {
		return "\ncmf.form.checkbox.select('". $this->getId() ."', ". ($this->getValue()===$this->getYes() ? 'true' : 'false') .");";
	}

}

?>