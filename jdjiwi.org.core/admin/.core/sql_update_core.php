<?php

class sql_update_core extends admin_registry_core {

    // --------------- обновление данных, кеша и других связанных с записьями данных ---------------
    private $data = array();
    private $mList = array();

    public function start() {
        if (!empty($this->data)) {
            if ($this->parent()->model()) {
                call_user_func(array($this->parent()->model(), 'update'), $this->mList, $this->data);
            }
        }
    }

    public function set($list, $send) {
        if (!is_array($send)) {
            $send = array($send => $send);
        }
        $this->data = array_merge($this->data, $send);
        foreach ((array) $list as $id) {
            $this->mList[$id] = $id;
        }
    }

//    public function getUpdateId() {
//        return $this->id()->get();
//    }
}

?>