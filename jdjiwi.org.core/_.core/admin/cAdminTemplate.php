<?php

class cAdminTemplate {

    private $item = null;
    private $mData = array();

    public function __construct() {
        $this->setTeplate('admin.index.php');
    }

    // хеш шаблона
    private function hash() {
        return cHashing::hash($this->item);
    }

    // шаблоны
    private function setTeplate($t) {
        $this->item = $t;
    }

    private function template($content) {
        if (cAjax::is()) {
            $this->item = str_replace('.php', '.ajax.php', $this->item);
        }
        include($this->item);
    }

    // старт страницы
    public function start() {
        cBuffer::start();
        $conf = cPages::getPageConfig(cPages::getMain());

        ob_start();
        $this->setTeplate(cPages::template()->get($conf->template));
        cAdmin::router()->start($conf->path);
        if (cAdmin::router()->command()->isNoRecord()) {
            $this->phtml('_404');
        } else {
            $this->phtml($conf->path);
        }
        $this->template(ob_get_clean());
        cBuffer::output();
    }

    // обработка страницы
    private function page($page) {
        $conf = cPages::getPageConfig($page);
        if (empty($conf))
            return false;

        ob_start();
        cPages::setItem($page);
        $this->php($conf->path);
        $this->phtml($conf->path);
        return ob_get_clean();
    }

    // переменные шаблоны
    public function assing($name, $value) {
        $this->mData[$name] = $value;
    }

    // выполнение php файла
    private function php($p58) {
        return require($p58 . '.php');
    }

    // выполнение шаблона
    private function phtml($p58) {
        unset($this->mData['p58']);
        extract($this->mData);
        $this->mData = array();
        if (!is_file($p58)) {
            $p58 = preg_replace('~^([^/]+)\/([^/]+)$~S', '$1/templates/$2', $p58);
        }
        require($p58 . '.phtml');
    }

}

?>