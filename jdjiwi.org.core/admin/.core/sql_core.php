<?php

abstract class sql_core extends admin_core {

    // настройки
    public function settings() {
        return self::register('sql_settings_core', self::personal);
    }

    // настройки
    public function update() {
        return self::register('sql_update_core', self::personal);
    }

    /* === выборка данных === */

    public function getIdList($where = array(1), $id = 'id') {
        return $this->sql()->placeholder("SELECT ?field FROM ?t WHERE ?w", $id, $this->settings()->table(), $where)
                        ->fetchRowAll(0, 0);
    }

    public function isRecord($id) {
        return $this->sql()->placeholder("SELECT 1 FROM ?t WHERE ?w", $this->settings()->table(), $this->settings()->whereId($id))
                        ->numRows();
    }

    public function getDataRecord($id) {
        return $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->settings()->table(), $this->settings()->whereId($id))
                        ->fetchAssoc();
    }

    public function getDataWhere($where) {
        return $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->settings()->table(), $where)
                        ->fetchAssoc();
    }

    public function getFeildRecord($field, $id) {
        return $this->sql()->placeholder("SELECT ?field FROM ?t WHERE ?w", $field, $this->settings()->table(), $this->getsettings()->whereId($id))
                        ->fetchRow(0);
    }

    public function getFeildsRecord($fields, $id) {
        return $this->sql()->placeholder("SELECT ?fields FROM ?t WHERE ?w", $fields, $this->settings()->table(), $this->getsettings()->whereId($id))
                        ->fetchRow();
    }

    public function getDataList($where) {
        return $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w ORDER BY ?o", $this->settings()->table(), $where, $this->settings()->sort())
                        ->fetchAssocAll('id');
    }

    public function getNameList($filter = null, $fileds = null, $isName = true) {
        if (is_null($filter))
            $filter = $this->getWhereFilter();
        if (is_array($fileds)) {
            if ($isName)
                array_push($fileds, 'id', 'name');
            else
                array_push($fileds, 'id');
        } else {
            if ($isName)
                $fileds = array('id', 'name');
            else
                $fileds = array('id');
        }

        return $this->sql()->placeholder("SELECT ?f FROM ?t WHERE ?w ORDER BY ?o", $fileds, $this->settings()->table(), $filter, $this->settings()->sort())
                        ->fetchAssocAll('id');
    }

    public function getNameListGroup(array $where, $group) {
        return $this->sql()->placeholder("SELECT COUNT(*) AS count, ?field FROM ?t WHERE ?w GROUP BY ?field", $group, $this->settings()->table(), $where, $group)
                        ->fetchRowAll(1, 0);
    }

    /* === выборка данных === */
}

?>