<?php


abstract class driver_modul_gallery_list_edit extends driver_modul_gallery_edit {


    protected function updateIsErrorData($data, &$isError) {
        if(!$this->id()->get() and !isset($data['image'])) {
            $isError = true;
            $this->form()->get()->setError('image', 'Выберите файл');
        }
        return $isError;
    }

}

?>
