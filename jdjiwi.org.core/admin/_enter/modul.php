<?php

class _enter_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        // формы
        $form = $this->form()->get();
        $form->add('loginMain', 'email')
                ->name('Логин')->default('Логин')->isNoEmpty()->strMin(2)->strMax(40)->isSpecialchard();
        $form->add('passwordMain', 'password')
                ->name('Пароль')->default('Пароль')->isNoEmpty();
    }

    protected function runData() {
        return array();
    }

    protected function updateIsErrorData($data, &$isError) {
        if (empty($data['loginMain']) or empty($data['passwordMain']))
            return;
        $admin = new cmfAdmin();
        if ($admin->select($data['loginMain'], $data['passwordMain'])) {
            $this->ajax()->reload();
        } else {
            $isError = true;
            $this->form()->get()->setError('loginMain', 'Логин или пароль не верны!');
        }
    }

    public function getJsSetData($update = false) {
        return parent::getJsSetData(false);
    }

}

?>