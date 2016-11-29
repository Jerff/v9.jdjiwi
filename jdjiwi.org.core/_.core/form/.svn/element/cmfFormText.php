<?php


class cmfFormText extends cmfFormElement {
    private $type = 'text';
    private $default = null;

    protected function init($o) {
        if(isset($o['default'])) {
            $this->setDefault($o['default']);
        }
        parent::init($o);
    }
    protected function setType($type) {
        $this->type = $type;
    }
    protected function getType() {
        return $this->type;
    }

    public function setDefault($d) {
        $this->default = $d;
        $this->setFilter('cmfIsNotDefault', $d);
    }
    protected function getDefault() {
        return $this->default;
    }

    public function getValue() {
        $v = parent::getValue();
        if($v==='' or is_null($v)) {
            $v = $this->getDefault();
        }
        return $v;
    }

    public function html($param, $style='') {
        return '<input type="'. $this->getType() .'" name="'. ($name=$this->getId()) .'" id="'. $name .'" value="'. cString::specialchars($this->getValue()) .'" '. $style .' />';
    }

}


class cmfFormTextInt extends cmfFormText {
    protected function init($o) {
        if(isset($o['min'])) $this->setFilter('cmfFilterIntMin', $o['min']);
        if(isset($o['max'])) $this->setFilter('cmfFilterIntMax', $o['max']);
        unset($o['min'], $o['max']);

        $this->setReform('cmfReformInt', false, true);
        parent::init($o);
    }
}

class cmfFormTextFloat extends cmfFormText {
    protected function init($o) {
        if(isset($o['min'])) $this->setFilter('cmfFilterIntMin', $o['min']);
        if(isset($o['max'])) $this->setFilter('cmfFilterIntMax', $o['max']);
        unset($o['min'], $o['max']);

        $this->setReform('cmfReformFloat', false, true);
        parent::init($o);
    }

}


class cmfFormTextDate extends cmfFormText {
    function __construct($out=null, $in=null, $o=null) {
        parent::__construct($o);

        if(!$in) $in = '{Y}-{m}-{d} {H}:{M}:{S}';
        if(!$out) $out = '{d}-{m}-{Y}';
        $this->setReform('cmfReformDate', $in, $out);
    }
}


class cmfFormTextDateTime extends cmfFormText {
    function __construct($out=null, $in=null, $o=null) {
        parent::__construct($o);

        if(!$in) $in = '{Y}-{m}-{d} {H}:{M}:{S}';
        if(!$out) $out = '{d}-{m}-{Y} {H}:{M}';
        $this->setReform('cmfReformDate', $in, $out);
    }
}


class cmfFormHidden extends cmfFormText {
    protected function init($o) {
        $this->setType('hidden');
        parent::init($o);
    }
}

class cmfFormValue extends cmfFormText {

	public function html($param, $style='') {
		return $this->getValue();
	}

	public function htmlOld() {
		return null;
	}

	public function processing($data, $old, $upload) {
		return null;
	}

	public function jsUpdate() {
        return '';
	}
	protected function jsUpdateValue() {
        return '';
	}
    public function jsUpdateOld() {
        return '';
    }

}

?>
