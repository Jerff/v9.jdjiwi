<?php

class cFormProcessing extends cFormCore {

    public function handler($isChange = false) {
        return $this->processing($isChange, false);
    }

    public function result($isChange = true, $isUpload = true) {
        $send = $this->processing($isChange, $isUpload);
        $this->form()->select($send);
        return $send;
    }

    private function &processing($isChange = true, $isUpload = true) {
        $this->form()->clear();
        $sent = array();
        $this->security()->isValid();
        foreach ($this->form()->all() as $name => $el) {
            $value = $el->processing($isChange, $isUpload);
            if (!is_null($value)) {
                $sent[$name] = $value;
            }
        }
        return $sent;
    }

}

?>