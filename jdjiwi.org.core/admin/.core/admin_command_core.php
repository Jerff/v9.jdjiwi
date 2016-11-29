<?php

class admin_command_core {

    static private $mCommand = array();
    static private $mIgnore = array();
    static private $mReplace = array();

    private function is($k) {
        return isset(self::$mCommand[$k]) and !isset(self::$mIgnore[$k]);
    }

    // установка команды
    private function set($command) {
        if (isset(self::$mReplace[$command])) {
            self::$mCommand[self::$mReplace[$command]] = true;
        } else {
            self::$mCommand[$command] = true;
        }
    }

    // рекурсия вызовов команд
    private function recursion($handler, $recursion = 1) {
        return admin_command_recursion_core::init($this, $handler, $recursion);
    }

    // игноривание команды
    public function ignore() {
        return $this->recursion('_ignore');
    }

    public function _ignore($command) {
        self::$mIgnore[$command] = true;
    }

    // замена команд
    public function replace() {
        return $this->recursion('_replace', 2);
    }

    public function _replace($command, $replace) {
        self::$mReplace[$command] = $replace;
    }

    // проверка команд
    public function isReload() {
        return $this->is('reload');
    }

    public function isNewView() {
        return $this->is('reloadView');
    }

    public function isNewRecord() {
        return $this->is('newRecord');
    }

    public function isNoRecord() {
        return $this->is('noRecord');
    }

    // задание команд
    public function reload() {
        $this->set('reload');
    }

    public function reloadView() {
        $this->set('reloadView');
    }

    public function newRecord() {
        $this->set('newRecord');
    }

    public function noRecord() {
        $this->set('noRecord');
    }

}

?>