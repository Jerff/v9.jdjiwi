<?php

class admin_filter_core extends admin_core {

    public function isSingleton() {

    }

    // инициализация фильтров
    public function init() {

    }

    // установка & получение фильтра
    public function set($n, $v) {
        cInput::get()->set($n, $v);
    }

    public function get($n) {
        return urldecode(cInput::get()->get($n));
    }

    public function setEncode($n, $v) {
        $this->set($n, $v);
    }

    // старт фильтра
    public function start($mFilter, $name, $sort = 'reset') {
        $id = $this->get($name);
        if (!isset($mFilter[$id])) {
            if ($sort === 'reset') {
                reset($mFilter);
                $id = key($mFilter);
            } elseif ($sort === 'end') {
                end($mFilter);
                $id = key($mFilter);
            } else {
                if (isset($mFilter[$sort])) {
                    $id = $sort;
                } else {
                    $id = key($mFilter);
                }
            }
        }

        $this->setEncode($name, $id);
        if (isset($mFilter[$id]))
            $mFilter[$id]['sel'] = true;

        $url = $this->url()->getSubmit(array($name => null, 'page' => 1));
        foreach ($mFilter as $key => &$value) {
            $value['url'] = $url . '&' . urlencode($name) . '=' . urlencode($key);
        }
        $this->info()->set('filterKey-' . $name, $id);
        $this->info()->set('filter-' . $name, $mFilter);
    }

    public function item($key, $id = null) {
        if ($id) {
            return new cmfData(get($this->item($key), $id));
        } else {
            $this->info()->get('filter-' . $key);
        }
    }

    public function value($key) {
        return $this->info()->get('filter-' . $key);
    }

}

?>