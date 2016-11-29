<?php

class sql_position_core extends admin_registry_core {

    private $where = array();

    public function setWhere($where) {
        $this->where = $where;
    }

    public function where() {
        return $this->where;
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

}

?>