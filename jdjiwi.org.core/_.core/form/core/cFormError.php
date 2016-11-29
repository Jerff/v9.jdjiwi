<?php

class cFormError extends cFormCore {

    private $mError = array();

    public function set($key, $value) {
        $this->mError[$key] = $value;
    }

    public function setSecurity($value) {
        $this->set('form-security', $value);
    }

    public function is() {
        return (bool) $this->mError;
    }

    public function clear() {
        $this->mError = array();
    }

    public function errorId() {
        return $this->form()->name('error');
    }

    public function view() {
        ?><p class="formError" id="<?= $this->errorId() ?>"><?= $this->parent()->config()->error()->submit ?></p><?
    }

    public function update() {
        $mHidden = array();
        foreach ($this->form()->all() as $name => $el) {
            if (isset($this->mError[$name])) {
                if ($el->settings()->isErrorHide) {
                    $mHidden[] = $this->mError[$name];
                    cAjax::get()->script('cForm.error.color.show("' . $el->id() . '", "' . $this->form()->settings()->color . '");');
                } else {
                    cAjax::get()->script('cForm.error.show("' . $el->id() . '", "' . $el->errorId() . '", "' . $this->form()->settings()->color . '", "' . cJScript::quote($this->mError[$name]) . '");');
                }
                unset($this->mError[$name]);
            } else {
                cAjax::get()->script('cForm.error.hide("' . $el->id() . '", "' . $el->errorId() . '");');
            }
        }
        if (!empty($mHidden)) {
            cAjax::get()->script('cForm.error.alert("' . cJScript::quote($this->config()->error()->form) . '", "' . cJScript::quote(implode('<br>', $mHidden)) . '");');
        }
        if (isset($this->mError[$name = 'form-security'])) {
            cAjax::get()->script('cForm.error.alert("' . cJScript::quote($this->mError[$name = 'form-security']) . '");');
            unset($this->mError[$name]);
        }
        if (empty($this->mError)) {
            cAjax::get()->script(
                    cJScript::queryId($this->errorId())->hide()
            );
        } else {
            cAjax::get()->script(
                    cJScript::queryId($this->errorId())->html(implode('<br>', $this->mError))->show()
            );
        }
        $this->clear();
    }

}
?>