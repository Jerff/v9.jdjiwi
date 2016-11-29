<?php

class cJScript {

    //cmfToJsString(
    //cJScript::toString(
    //cJScript::quote(

    /* === преобразованны mix данные в строки javascript-а === */
    static public function quote($d) {
        if (is_string($d))
            return str_replace(array("\n", "\r"), array('\n', '\r'), addslashes($d));
        if (is_array($d)) {
            reset($d);
            while (list($k, $v) = each($d))
                $d[$k] = self::quote($v);
            return $d;
        }
        return self::quote((string) $d);
    }

    static public function encode($command) {
//        if(cDebug::isAjax()) {
//            return $command;
//        }
        $command = (string) $command;
        $encode = '';
        $length = strlen($command);
        for ($i = 0; $i < $length; $i++) {
            $encode .= ord($command{$i}) . '!';
        }
        $encode = <<<HTML
new(function(){
    var t="",i,c=0,o="";
    var s="{$encode}";
    l=s.length;
    while(c<=s.length-1){
        while(s.charAt(c)!='!')t=t+s.charAt(c++);
        c++;
        o=o+String.fromCharCode(t);
        t="";
    }
    $.globalEval(o);
});
HTML;
        return preg_replace('~(\s+)~', ' ', $encode);
    }

    /* === /преобразованны mix данные в строки javascript-а === */



    /* === инициация === */

    private $mCommand = array();
    private $command = '';

    public function __construct($selected) {
        $this->command = '$("' . $selected . '")';
    }

    /* === выборка значений === */

    static public function queryId($id) {
        return self::query('#' . $id);
    }

    static public function queryClass($class) {
        return self::query('.' . $class);
    }

    static public function query($selected) {
        return new cJScript($selected);
    }

    /* === выборка значений === */

    // преобразовать в строку
    public function __toString() {
        $this->mCommand[] = $this->command . ';';
        return implode("\n", $this->mCommand);
    }

    // добавить команду
    protected function &add($js) {
        $this->command .= $js;
        return $this;
    }

    /* === инициация === */







    /* === hide, show, attr, val === */

    public function hide() {
        return $this->add('.hide()');
    }

    public function show() {
        return $this->add('.show()');
    }

    public function attr($option) {
        foreach ($option as $key => $value) {
            $option[$key] = $key . ': "' . self::quote($value) . '"';
        }
        return $this->add('.attr({' . implode(',', $option) . '})');
    }

    public function val($value) {
        return $this->add('.val("' . self::quote($value) . '")');
    }

    public function html($value) {
        return $this->add('.html("' . self::quote($value) . '")');
    }

    /* === hide, show === */
}

?>