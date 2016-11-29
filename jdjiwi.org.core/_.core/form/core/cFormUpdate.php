<?php

class cFormUpdate extends cFormCore {

    public function js($isOldUpdate = true) {
        $this->security()->update();
        $this->error()->update();
        foreach ($this->form()->all() as $el) {
            $el->update($isOldUpdate);
        }
    }

}

?>