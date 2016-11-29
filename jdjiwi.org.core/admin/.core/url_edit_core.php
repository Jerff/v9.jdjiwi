<?php

class url_edit_core {

    private $mUrl = array();
    private $mHandler = array();

    // установка обрабатывающий параметры функции
    protected function setHandler($name, $handler) {
        if(!empty($handler)) $this->mHandler[$name] = $handler;
    }
    // обработка параметров адреса
    protected function initOption($name, &$opt) {
        if(isset($this->mHandler[$name])) {
            $handler = $this->mHandler[$name];
            $handler($opt);
        }
    }

    // генерация строки get-запроса
    protected function requestUri($opt = null) {
        $get = cInput::get()->all();
        if (!is_null($opt))
            $get = array_merge($get, (array) $opt);
        return cConvert::arrayToUri($get);
    }

    /* === одна запись === */

    // установка адресов
    public function set($name, $url, $handler = false) {
        $this->mUrl[$name] = cUrl::admin()->get($url);
        $this->setHandler($name, $handler);
    }

    public function get($name, $opt = null) {
        $this->initOption($name, $opt);
        return get($this->mUrl, $name) . $this->requestUri($opt);
    }

    // адрес без параметров
    public function notParam($name) {
        return get($this->mUrl, $name);
    }

    // submit
    public function setSubmit($url, $handler = null) {
        $this->set('submit', $url, $handler);
    }

    public function getSubmit($opt = null) {
        return $this->get('submit', $opt);
    }

    // catalog
    public function setCatalog($url, $handler = null) {
        return $this->set('catalog', $url, $handler);
    }

    public function getCatalog($opt = null) {
        $opt['id'] = null;
        $opt['parentId'] = null;
        return $this->get('catalog', $opt);
    }

}

?>