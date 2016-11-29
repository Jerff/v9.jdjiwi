<?php

class url_list_core extends url_edit_core {

    public function setEdit($url, $handler = null) {
        $this->set('edit', $url, $handler);
    }

    public function getEdit($opt = null) {
        $opt['parentId'] = null;
        return $this->get('edit', $opt);
    }

    public function setNew($url, $handler = null) {
        $this->set('new', $url, $handler);
    }

    public function getNew($opt = null) {
        $opt['id'] = $opt['parentId'] = null;
        return $this->get('new', $opt);
    }

}

?>