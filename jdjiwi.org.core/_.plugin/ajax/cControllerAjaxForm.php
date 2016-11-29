<?php

class cControllerAjaxForm extends cPatternsRegistry {

    private $mForm = array();

    public function set($count) {
        for ($i = 1; $i <= $count; $i++) {
            $this->mForm[$i] = new cForm('', $this->parent()->hash('form' . $i));
        }
        $this->mForm[1]->settings()->security();
    }

    public function &get($id = 1) {
        return $this->mForm[$id];
    }

    public function &all() {
        return $this->mForm;
    }

    public function count() {
        return count($this->mForm);
    }

}

?>