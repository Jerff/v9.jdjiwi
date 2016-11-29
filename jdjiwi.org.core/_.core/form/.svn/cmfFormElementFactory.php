<?php


abstract class cmfFormElement {

    private $id = null;
    private $form = false;
    private $viewError = true;

    private $name = null;
    protected $value = null;

    private $old = true;

    private $filter = null;
    private $reform = null;

    private $label = null;

    function __construct($o=null) {
        foreach((array)$o as $k=>$v) {
            if(is_numeric($k)) {
                $o[$v] = true;
            }
        }
        $this->init($o);
    }

    protected function init($o) {
        // устанавливаем опции
        if(isset($o['name']))          $this->setName($o['name']);
        if(isset($o['old']))           $this->setOld($o['old']);

        if(isset($o['phoneCod']))       $this->setReform('cmfFilterPhoneCod', false, true);
        if(isset($o['phonePostPrefix']))$this->setReform('cmfFilterPhonePostPrefix', false, true);

        // смотрим преобразования
        if(isset($o['int']))           $this->setReform('cmfReformInt', false, true);
        if(isset($o['length']))        $this->setReform('cmfReformStrLen', $o['length']);
        if(isset($o['float']))         $this->setReform('cmfReformFloat', false, true);
        if(isset($o['textFormat']))    $this->setReform('cmfReformTextFormat', $o['textFormat']);
        if(isset($o['RusToEn']))       $this->setReform('cmfReformRusToEn');
        if(isset($o['specialchars']))  $this->setReform('cmfReformSpecialchars', false, $o['specialchars']);
        if(isset($o['uri'])) {
             $this->setFilter('cmfFilterLenMax', $o['uri']);
             $this->setReform('cmfReformUri', false, $o['uri']);
        }


        if(isset($o['min']))           $this->setFilter('cmfFilterLenMin', $o['min']);
        if(isset($o['max']))           $this->setFilter('cmfFilterLenMax', $o['max']);
        if(isset($o['errorHide']))    $this->setErrorHidden();

        if(isset($o['label']))         $this->setLabel($o['label']);

        if(isset($o['datetime'])) {
            $in = get($o, 'inside', '{Y}-{m}-{d} {H}:{M}:{S}');
            $out = get($o, 'outside', '{d}-{m}-{Y} {H}:{M}:{S}');
            $this->setReform('cmfReformDate', $in, $out);
        }


        // смотрим фильтры
        if(isset($o['!empty']))        $this->setFilter('cmfNotEmpty');
        if(isset($o['empty']))         $this->setFilter('cmfIsEmpty');
        if(isset($o['email']))         $this->setFilter('cmfIsEmail');
        if(isset($o['phone']))         $this->setFilter('cmfIsPhone');
        if(isset($o['url']))           $this->setFilter('cmfIsUrl');
        if(isset($o['httpUrl']))       $this->setFilter('cmfIsHttpUrl');
        if(isset($o['formatLengt']))   $this->setFilter('cmfFilterTextLengt', $o['formatLengt']);

        // дополнительная инициализация в потомках
    }

    // ссылка на форму
    public function setForm(&$form, $id) {
        $this->form = $form;
        $this->id = $id;
    }
    protected function form() {
        return $this->form;
    }


    // имя формы
    protected function getId() {
        return $this->form()->getId($this->id);
    }
    protected function getElementId() {
        return $this->id;
    }
    protected function getError() {
        return $this->form()->getErrorElement($this->id);
    }
    protected function getErrorId() {
        return 'error_'. $this->getId();
    }
    protected function getOldId() {
        return 'old_'. $this->getId();
    }


    public function getTypeElement() {
        return '';
    }
    public function isTypeElement($type) {
        return $type===$this->getTypeElement();
    }


    public function reset() {
        $this->value = null;
    }
    public function resetElement() {
    }


	public function isFile() {
		return false;
	}
/*    private function setLabel($label) {
        $this->label = $label;
    }
    protected function getLabel() {
        return $this->label;
    }*/

    // ------------- преобразования -------------
    // установить преобразование данных
    public function setReform($name, $in=null, $out=null) {
        if(function_exists($name)) {
            if(is_null($out)) $out = $in;
            if($out===$in) $this->reform[$name] = $in;
            else $this->reform[$name] = array('in'=>$in, 'out'=>$out);
        }
    }

    // получить данных о преобразованиях
    protected function getReform() {
        return is_array($this->reform) ? $this->reform : array();
    }
    // ------------- /преобразования -------------


    // ------------- фильтры -------------
    // установить фильтры данных
    public function setFilter($name, $data=null) {
        if(function_exists($name)) $this->filter[$name] = $data;
    }

    // получить данных о фильтры
    protected function getFilter() {
        return is_array($this->filter) ? $this->filter : array();
    }

    public function NotEmpty() {
        return isset($this->filter['cmfNotEmpty']);
    }
    // ------------- /фильтры -------------


    // устанавлиет флаг - проверять/не проверять на неизменившиеся значения
    public function setOld($value=true) {
        $this->old = (bool)$value;
    }

    public function getOld($value=true) {
        return $this->old;
    }

    // установить имя элемента
    public function setName($name) {
        $this->name = $name;
    }
    // вернуть имя элемента
    public function getName() {
        return $this->name;
    }


    // установка данных
    public function select($value, $data=null) {
        $this->value = $value;
    }
    public function selectAll($data) {
    }

    protected function reformAll($value) {
        foreach($this->getReform() as $reform=>$opt) {
            if(is_array($opt)) $value = $reform($value, $opt['out'], $opt['in']);
            else $value = $reform($value, $opt);
        }
        return $value;
    }

    // возвращает значение элемента (отформатированное)
    public function getValue() {
        return  $this->getValueFormat($this->value);
    }

    // форматирует данные для вывода
    public function getValueFormat($value) {
        foreach($this->getReform() as $reform=>$opt) {
            if(is_array($opt)) {
                $value = $reform($value, $opt['in'], $opt['out']);
            } else {
                $value = $reform($value, $opt);
            }
        }
        return $value;
    }

    protected function &getValues() {
        return $this->value;
    }
    protected function getValuesChild($key) {
        return get($this->value, $key);
    }

    // старое значение элемента
    public function getValueOld() {
        return $this->value;
    }


    // работа с файлами
    // удаление файлов формы
    public function deleteFile(&$row) {
    }
    // копирование файлов формы
    public function copyFile(&$row, $name) {
    }


    // ошибки в заполнении не отображаниются
    protected function setErrorHidden() {
        $this->viewError = false;
    }
    public function isErrorView() {
        return $this->viewError;
    }

    // отображения
    public function htmlError($s=null, $s2=null) {

        if($this->isErrorView()) {
            return '<span class="formElement">'. $s . $s2 .'</span>';
        } else {
            return $s . $s2;
        }

    }

    public function viewError() {
        return '<span class="formError cmfHide" id="'. $this->getErrorId() .'"></span>';
    }

    public function htmlView($param, $style='') {
        return $this->htmlError(    $this->html($param, $style),
                                    $this->htmlOld());
    }

    abstract public function html($param, $style='');



    public function label($value) {
        return '<label for="'. $this->getId() .'">'. $value .'</label>';
    }

    public function htmlOld() {
        return '<input type="hidden" name="'. ($name=$this->getOldId()) .'" id="'. $name .'" value="'. cString::specialchars($this->getValueOld()) .'" />';
    }


    // обновление формы
    public function jsUpdate() {
        $id = $this->getId();
        $error = cJScript::quote($this->getError());
        $js = '';
        if($this->isErrorView()) {
            $js ="\ncmf.form.error.view('{$id}', '". $this->getErrorId() ."', '{$error}');";
        }
        $js .= $this->jsUpdateValue();
        $js .= "\ncmf.form.error.color('{$id}', ". strlen($error) .", '". $this->form()->getOption('color') ."');";
        return $js;
    }
    protected function jsUpdateValue() {
        $id = $this->getId();
        return "\ncmf.form.error.setValue('{$id}', '". cJScript::quote($this->getValue()) ."');";
    }
    public function jsUpdateOld() {
        return "\ncmf.form.error.setValue('". $this->getOldId() ."', '". cJScript::quote($this->getValueOld()) ."');";
    }


    // обработка данных
    public function processing($data, $old, $upload) {
        $value = get($data, $this->getId());
        foreach($this->getReform() as $reform=>$opt) {
            if(is_array($opt)) $value = $reform($value, $opt['out'], $opt['in']);
            else $value = $reform($value, $opt);
        }
        foreach($this->getFilter() as $filter=>$opt) {
            if(!$filter($value, $opt)) {
                $this->select($value);
                return $old ? null : '';
            }
        }
        // проверяем изменились ли данные?
        if($this->getOld() and $old) {
            $valueOld = get($data, $this->getOldId());
            if($value===$valueOld) return null;
            else if($value==$valueOld) return null;
        }
        $this->select($value);

        if(is_null($value) and !$old) $value = '';
        return $value;
    }

}

?>
