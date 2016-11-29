<?php


class cmfFormRadio extends cmfFormSelect {

    public function htmlError($s=null, $s2=null) {
        return '<span class="formRadio">'. $s . $s2 .'</span>';
    }

    public function html($param, $style='') {
        $name = $this->getId();
        $values = $this->getValues();
        if(is_null($param)) {
            $str = '';
            foreach($this->getValueArray() as $key) {
                $select = isset($values['sel'][$key]) ? 'checked' : '';
                $str .= " <label><input $style type=\"radio\" name=\"$name\" value=\"". cString::specialchars($key) ."\" {$select} id=\"{$name}_{$key}\" />"
                        ."&nbsp;". $values['array'][$key] ."&nbsp;</label>";
            }
            return $str;
        }

        if(is_array($param)) {
            $param2 = get($param, 1);
            $param3 = get($param, 2);
            $param = array_shift($param);
        } else $param2 = $param3 = null;

        $select = isset($values['sel'][$param]) ? 'checked' : '';
        $label = ($param3 ? $param3 : $values['array'][$param]);
        $option = $this->getOptionsStr($param);
        $input = "<input $style type=\"radio\" name=\"$name\" value=\"". cString::specialchars($param) ."\" {$select} {$option} id=\"{$name}_{$param}\" />";

        if($param2==='radio' or !$label) {
            return $input;
        } elseif($param2==='label') {
            return "<label for=\"{$name}_{$key}\">&nbsp;{$label}&nbsp;</label>";
        }
        return "<label>{$input}&nbsp;{$label}&nbsp;</label>";
    }

    public function htmlOld() {
        static $is = array();
        if(isset($is[$this->getId()])) return '';
        $is[$this->getId()] = true;
        return parent::htmlOld();
    }



    public function jsUpdateValue() {
        $name = $this->getId();
        $js ='';
        $_sel = $this->getValuesSelect();
        foreach($this->getValuesAll() as $k=>$v) {
            $js .="\ncmf.form.radio.select('{$name}_{$k}', ". (isset($_sel[$k]) ? 'true' : 'false') .");";
        }
        return $js;
    }

}


class cmfFormRadioInt extends cmfFormRadio {
    protected function init($o) {
        $this->setReform('cmfReformInt', false, true);
        parent::init($o);
    }
}


class cmfFormRadioAll extends cmfFormRadio {
    public function jsUpdateValue() {
        $name = $this->getId();
        $content = cJScript::quote($this->html($name));
        return "\n$('#{$name}_all').html('$content');";
    }
    public function htmlError($s=null, $s2=null) {
        return '<span class="formRadioAll" id="'. $name .'_all">'. $s . $s2 .'</span>';
    }
}

class cmfFormRadioAllInt extends cmfFormRadioAll {
    protected function init($o) {
        $this->setReform('cmfReformInt', false, true);
        parent::init($o);
    }
}

?>