<?php

abstract class driver_modul_edit extends admin_core {

    // данные
    public function data() {
        return self::register('modul_data_core');
    }

    // формы
    public function form() {
        return self::register('modul_form_core');
    }

    private $db = null;
    private $form = null;
    private $data = null;
    private $config = false;

    protected function init() {
        $this->form()->set($this->hash());
    }

    // установит & вернуть форму объект базы данных
    protected function setDb($db) {
        $this->db = $this->register($db);
    }

    public function &getDb() {
        return $this->db;
    }

    // установка и получение данных базы ---------------
    protected function setData(&$data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function getDataId($id) {
        return get($this->data, $id);
    }

    public function &current() {
        $current = array();
        $current[] = $this->form()->get();
        $current[] = new cmfData($this->getData());
        return $current;
    }

    // заполнение объекта данными из базы
    // выбираем данные
    // загружаем форму всему свойствами
    // заполняем форму данными из базы
    public function start() {
        if ($id = $this->id()->get())
            $this->loadData();
        $this->loadForm();
        if ($id) {
            $data = $this->getData();
            if ($data)
                $this->selectForm($data);
        }
    }

    // выборка данных записи из базы
    protected function loadData() {
        $data = $this->getDb()->loadData();
        $this->setData($data);
        return $data;
    }

    // заполнение формы данными из базы
    // форма должна до этого быть полностью заполненая параметрами
    // функция юзается iframe
    public function runForm() {
        if (!$this->id()->get())
            return;
        $this->runData();
        $data = $this->getData();
        $this->selectForm($data);
    }

    // заполняем форму данными
    public function selectFormInit() {
        $this->form()->get()->reset();
    }

    protected function selectForm($data) {
        $this->selectFormInit();
        $this->form()->get()->select($data);
    }

    // загружаем форму всему свойствами
    public function loadForm() {
        $this->form()->get()->reset();
    }

    public function updateIsError(&$isError) {
        $this->loadForm();
        $data = $this->form()->get()->handler();
        $this->setData($data);
        $isError |= $this->form()->get()->isError();
        $this->updateIsErrorData($data, $isError);
        $this->loadForm();
    }

    protected function updateIsErrorData($data, &$isError) {

    }

    protected function updateIsErrorDataUri($page, $uri, $name, $length, $where) {
        if (!$uri) {
            $name = $this->form()->get()->handlerElement($name);
            $uri = cmfReformUri($name, $length);
        }
        if (cmfContentUrl::isUrlExists($page, $this->id()->get(), $uri)
                or $this->getDb()->isUrlExistsWhere($uri, $where)) {
            $this->form()->get()->setError('uri', 'Адрес "/' . $uri . '/" уже занят!');
            return true;
        }
        return false;
    }

    protected function processingForm($id) {
        return $this->form()->get()->processing($id);
    }

    public function updateOk() {
        if (!cGlobal::is('updateOk')) {
            $id = (bool) $this->id()->get();
            cGlobal::set('updateOk', $id);
        } else {
            $id = cGlobal::get('updateOk');
        }
        $send = $this->processingForm($id);
        if (count($send)) {
            if ($id) {
                $data = $this->getDb()->runData();
                $this->selectForm($data);
                foreach ($send as $k => $v) {
                    $this->form()->get()->deleteFile($k, $row);
                }
            }

            $this->selectForm($send);
            $this->save($send);
            return true;
        }
        return false;
    }

    public function saveLineOk($name) {
        $this->command()->reloadView();
        $this->form()->get()->changeName($name);
        $this->updateOk();
    }

    public function updateError() {
        $this->loadForm();
        $send = $this->form()->get()->handler();
        $isError = false;
        $this->updateIsErrorData($send, $isError);
    }

    public function getUpdateError() {
        return $this->form()->get()->getError();
    }

    public function getJsSetData($update = true) {
        return $this->form()->get()->jsUpdate($update);
    }

    public function getListFile() {
        return $this->form()->get()->getListFile();
    }

    // запись данных формы с базу
    public function save($send) {
        $this->saveStart($send);
        if (count($send)) {
            $this->getDb()->save($send);
            $this->saveEnd($send);
            return true;
        }
    }

    public function setNewPos($reload = true) {
        $this->isPos = $reload;
    }

    public function getNewPos() {
        return $this->isPos;
    }

    protected function saveStart(&$send) {
        if (empty($send['pos']))
            if ($this->getNewPos() and !$this->id()->get()) {
                $send['pos'] = $this->getDb()->startSavePos($send);
            } elseif (isset($send['pos']) and empty($send['pos'])
                    and ($this->form()->get()->is('pos') or $this->getNewPos())) {
                $send['pos'] = $this->getDb()->startSavePos($send);
            }
        if (!$this->id()->get()) {
            $this->id()->set(0);
        }
    }

    protected function saveStartUri(&$send, $name, $len, $where = null) {
        if (empty($send['uri'])) {
            $uri = $this->form()->get()->handlerElement('uri');
            if (!$uri) {
                $name = $this->form()->get()->handlerElement($name);
                if ($name)
                    $send['uri'] = cmfReformUri($name, $len);
            }
        }
        if (isset($send['uri'])) {
            if ($where) {
                $send['uri'] = $this->getDb()->updateIsUrlExistsWhere($send['uri'], $where);
            } else {
                $send['uri'] = $this->getDb()->updateIsUrlExists($send['uri']);
            }
        }
    }

    protected function saveEnd($send) {

    }

    public function deleteFile($element) {
        $this->run();
        $send = array();
        $this->form()->get()->deleteFile($element, $send);
        $this->save($send);
    }

    // удаление записи
    public function delete($list) {
        if (is_null($list))
            $list = $this->id()->get();
        $list = (array) $list;
        if (!count($list))
            return;

        $form = $this->form()->get();
        $this->getDb()->delete($form, $list);
        return $list;
    }

    public function getIdList($where) {
        return $this->getDb()->getIdList($where);
    }

    public function getNewLineData() {
        return $this->form()->get()->handler();
    }

}

?>
