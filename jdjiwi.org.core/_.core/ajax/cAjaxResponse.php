<?php

class cAjaxResponse {

    private $mScript = null;
    private $mHtml = null;
    private $mHtmlLog = array();

    public function log() {
        return array(
            'ajax-result' => '',
            'script' => $this->mScript,
            'html' => $this->mHtmlLog
        );
    }

    public function result() {
        $result = array();
        if (!empty($this->mScript)) {
            $result['script'] = $this->mScript;
        }
        if (!empty($this->mHtml)) {
            $result['html'] = $this->mHtml;
        }
        return $result;
    }

    /* === адреса === */

    public function hash($hash) {
        return $this->script("document.location.hash = '{$hash}';");
    }

    public function redirect($u) {
        $this->script("core.redirect('{$u}');");
        exit;
    }

    public function reload() {
        $this->script("core.reload();");
        exit;
    }

    /* === /адреса === */



    /* === скрипты === */

    public function script($js) {
        $this->mScript .= "\n" . $js;
        return $this;
    }

    // alert
    public function alert($content) {
        return $this->script('alert("' . cJScript::quote($content) . '");');
    }

    /* === /скрипты === */



    /* === html === */

    public function htmlId($id, $content) {
        return $this->html('#' . $id, $content);
    }

    public function html($id, $content) {
        if($id!=='#ajax-content' and cDebug::isAjax()) {
            $this->mHtmlLog[$id] = $content;
        }
        $this->mHtml[] = array(
            'selected' => $id,
            'content' => $content
        );
        return $this;
    }

    /* === /html === */



    /* === пользовательские скрипты === */

    public function assign() {
        if (func_num_args() < 3)
            return;

        $d = func_get_args();
        $k = cJScript::quote(array_shift($d));
        $v = cJScript::quote(array_pop($d));
        $js = "cmf.getId('$k')";

        reset($d);
        while (list(, $v2) = each($d)) {
            $js .= '.' . $v2;
        }
        $js .= " = '$v'";

        return $this->script($js);
    }

    /* === /пользовательские скрипты === */
}

?>