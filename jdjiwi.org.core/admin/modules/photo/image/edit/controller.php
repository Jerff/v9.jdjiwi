<?php

class photo_image_edit_controller extends driver_controller_gallery_edit {

    protected function init() {
        parent::init();
        $this->initModul('main', 'photo_image_edit_modul');

        // url
        $this->url()->setSubmit('/admin/photo/image/');
        $this->url()->setCatalog('/admin/photo/image/');
    }

    public function deleteProduct($id) {
        $old = cAdmin::menu()->sub()->getId();
        cAdmin::menu()->sub()->setId($id);
        $this->delete($this->getListId(array('photo' => $id)));
        cAdmin::menu()->sub()->setId($old);
    }

}

?>