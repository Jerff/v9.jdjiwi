<?php

abstract class driver_db_edit extends sql_core {
    /* === модель === */

    private $model = false;

    protected function setModel($model) {
        $this->model = $model;
    }

    protected function model() {
        return $this->model;
    }

    /* === /модель === */

    // данные
    public function pos() {
        return self::register('sql_position_core');
    }

    // обновления
    public function update() {
        return self::register('sql_update_core');
    }

    // --------------- загрузка и преобразование данных ---------------
    // --------------- выборка данных записи из базы по фильтру ---------------
    public function runData() {
        $data = $this->sql()->placeholder("SELECT ?f FROM ?t WHERE ?w", $this->getFields(), $this->getTable(), $this->getWhere())
                ->fetchAssoc();
        if (!$data) {
            $this->command()->notFount();
            return null;
        }
        $this->loadData($data);
        return $this->loadSetData($data);
    }

    // предобработка данных из базы
    public function loadData(&$data) {

    }

    // сохранение данных в массив для последующей передачи верхнему уровню
    protected function loadSetData(&$data) {
        return $data;
    }

    // --------------- запись данных в базу ---------------
    // запись данных
    public function save($send) {
        $old = $this->id()->get();
        $id = $this->saveId($this->id()->get(), $send);
        if ($old != $id) {
            $this->command()->newRecord();
        }
        $this->id()->set($id, $send);
        return $id;
    }

    // запись данных по $id
    public function saveId($id, $send) {
        $new = $this->sql()->add($this->getTable(), $send, $this->getWhereId($id));

        if (!$id)
            $id = $new;
        $this->saveEnd($id, $send);
        return $id;
    }

    protected function saveSetId($id) {
        if ($this->id()->get() != $id) {
            $this->command()->newRecord();
        }
        $this->id()->set($id);
    }

    // --------------- корректировка нумерации в базе ---------------
    // фильтр для коррекитировки
    protected function startSaveWhere() {
        return array();
    }

    protected function startSavePosField() {
        return 'pos';
    }

    // коррекитировка
    public function startSavePos(&$send) {
        $id = $this->id()->get();
        $where = array("a.`id`!='$id'");
        $join = $this->startSaveWhereJoin($send, $where);

        $table = $this->getTable();
        if ($join) {
            list($pos) = $this->sql()->placeholder("SELECT max(a.pos) FROM ?t a, ?t b WHERE ?w", $table, $table, $where)
                    ->fetchRow();
        } else {
            list($pos) = $this->sql()->placeholder("SELECT max(a.pos) FROM ?t a WHERE ?w", $table, $where)
                    ->fetchRow();
        }
        return (int) $pos + 1;
    }

    protected function startSaveWhereJoin($send, &$where) {
        $join = false;
        $fields = $this->startSaveWhere();

        $id = $this->id()->get();
        if ($id) {
            foreach ($fields as $name) {
                if (count($where))
                    $where[] = 'AND';
                if (isset($send[$name])) {
                    $where[] = $this->startSaveWhereJoinFieldValue('a', $name, $send[$name]);
                } else {
                    $where[] = $this->startSaveWhereJoinFieldTable('a', $name, 'b');
                    $join = true;
                }
            }
            if ($join) {
                $where[] = 'AND';
                $where[] = "b.`id`='$id'";
            }
        } else {
            foreach ($fields as $name) {
                if (isset($send[$name])) {
                    if (count($where))
                        $where[] = 'AND';
                    $where[] = $this->startSaveWhereJoinFieldValue('a', $name, $send[$name]);
                }
            }
        }
        return $join;
    }

    protected function startSaveWhereJoinFieldValue($t, $f, $v) {
        return $t . '.' . $this->sql()->quoteParam($f) . '=' . $this->sql()->quoteString($v);
    }

    protected function startSaveWhereJoinFieldTable($t1, $f1, $t2, $f2 = null) {
        return $t1 . '.' . $this->sql()->quoteParam($f1) . '=' . $t2 . '.' . $this->sql()->quoteParam(is_null($f2) ? $f1 : $f2);
    }

    // --------------- дейтсвия после запис в базу ---------------
    protected function updateController() {
        return false;
    }

    protected function saveEnd($id, $send) {
        if ($this->returnParent()) {
            cLoader::initModul($this->returnParent())->saveEnd($id, $send);
        }
        $this->update()->set($id, $send);
    }

    // --------------- обновление данных, кеша и других связанных с записьями данных ---------------
    private $update = array();
    private $updateList = array();

    public function runUpdateData() {
        if ($this->update) {
            if ($this->returnParent()) {
                $parent = cLoader::initModul($this->returnParent());
                $parent->updateData($this->updateList, $this->update);
                if ($parent->updateController()) {
                    call_user_func(array($parent->updateController(), 'update'), $this->updateList, $this->update);
                }
            } elseif ($this->updateController()) {
                call_user_func(array($this->updateController(), 'update'), $this->updateList, $this->update);
            } else {
                $this->updateData($this->updateList, $this->update);
            }
        }
    }

    public function setUpdateData($list, $send) {
        if (!is_array($send)) {
            $send = array($send => $send);
        }
        $this->update = array_merge($this->update, $send);
        foreach ((array) $list as $id) {
            $this->updateList[$id] = $id;
        }
    }

    public function getUpdateId() {
        return $this->id()->get();
    }

    public function updateData($list, $send) {

    }

    // --------------- проверка на присутсвие в базе похожих uri ---------------
    // фильтр проверки
    protected function getIsUrlExistsWhere() {
        return array(1);
    }

    // провека на присутсвтие урла в базе
    public function isUrlExistsWhere($url, $where = array(1)) {
        if ($this->id()->get()) {
            $id = (int) $this->id()->get();
            $where[] = "AND id!='{$id}'";
        }
        return $this->sql()->placeholder("SELECT 1 FROM ?t WHERE ?w AND uri=? ", $this->getTable(), $where, $url)
                        ->numRows();
    }

    // провека и корректировка на присутсвтие урла в базе
    public function updateIsUrlExists($url) {
        return $this->updateIsUrlExistsWhere($url, $this->getIsUrlExistsWhere());
    }

    // провека и корректировка на присутсвтие урла в базе по условию where
    public function updateIsUrlExistsWhere($url, $where) {
        if ($this->isUrlExistsWhere($url, $where)) {
            preg_match('~(^(.*?\_)([0-9]*)$)~', $url, $tmp);
            if (isset($tmp[3])) {
                $url = $tmp[2] . ($tmp[3] + 1);
            } else {
                $url = $url . '_1';
            }
            return $this->updateIsUrlExistsWhere($url, $where);
        }
        return $url;
    }

    // --------------- удаление записи ---------------
    public function delete(&$form, $list_id) {
        $table = $this->getTable();
        $list_id = (array) $list_id;
        $list_id = array_combine($list_id, $list_id);
        foreach ($list_id as $id) {
            $this->id()->set($id);
            $res = $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->getTable(), $this->getWhere());
            while ($row = $res->fetchAssoc()) {
                $form->select($row);
                $form->deleteFileAll();
            }
            $res->free();
        }

        $sql->del($table, $this->getWhereId($list_id));
        $sql->optimize($table);

        foreach ($list_id as $id) {
            $this->id()->set($id);
        }
        $parent = $this->returnParent() ? cLoader::initModul($this->returnParent()) : $this;
        if ($parent->updateController()) {
            call_user_func(array($parent->updateController(), 'delete'), $this->getDeleteModelId($list_id), $this->getUpdateParentId());
        }
    }

    protected function getDeleteModelId($list_id) {
        return $list_id;
    }

    protected function getUpdateParentId() {
        return array();
    }

}

?>