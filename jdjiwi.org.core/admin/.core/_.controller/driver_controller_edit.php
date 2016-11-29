<?php

// обстрактный контроллер страницы редактирования
abstract class driver_controller_edit extends controller_ajax_core {

    // адреса
    public function url() {
        return self::register('url_edit_core');
    }

    // тип текущего контрллера
    static protected function type() {
        return 'edit';
    }

    // siteUrl
    public function appUrl() {
        return self::register('appUrl_core');
    }

    // html код
    public function html() {
        return self::register('html_edit_core');
    }

    // view
//    public function view() {
//        return self::register('view_' . static::type() . '_core');
//    }

    /* === настройки === */

    function __construct($id = null) {
        if (!empty($id)) {
            $this->id()->set($id, true);
        }
        $this->init();
    }

    // инициализация
    protected function init() {

    }


    // проверка наличия элемента
    public function isRecord($id) {
        return (bool) $this->getModul()->getDb()->isRecord($id);
    }

    /* === /настройки === */



    /* === модули === */

    // модули контролера
    private $mModul = array();
    private $mUpdateModul = array();

    // добавить модуля (обновляемый/необновляемый) в контроллер
    protected function initModul($n, $m, $is = true) {
//        cLoader::initModul($m);
        $this->mModul[$n] = $this->register($m);
        if ($is) {
            $this->mUpdateModul[$n] = &$this->mModul[$n];
        }
    }

    protected function modul($n = 'main') {
        return $this->mModul[$n];
    }

//    protected function deleteModul($n) {
//        unset($this->mModul[$n], $this->mUpdateModul[$n]);
//    }

    protected function modulAll() {
        if ($this->isUpdate()) {
            return $this->mUpdateModul;
        } else {
            return $this->mModul;
        }
    }

    /* === /модули === */



    /* === загрузка данных объекта === */

    // загрузка объектов данными
    public function start() {
        foreach ($this->modulAll() as $m) {
            $m->start();
        }
    }

    // выбрать текущие данные
    private function &result() {
        $result = new cResult();
        foreach ($this->modulAll() as $n => $m)
            $result->$n = $m->result();
        return $result;
    }

    /* === /загрузка данных объекта === */



    /* === удаление === */

    public function delete($mList) {
        if ($mList) {
            $mList = (array) $mList;
            foreach ($this->modulAll() as $m)
                $m->delete($mList);

            foreach ((array) $mList as $id)
                cmfWysiwyng::delRecord($this->wysiwyngPath(), $id);
        }
        return $mList;
    }

    /* === /удаление === */

    // обновление форм
    // проверяем есть ли ошибки интерпритации формы и если нет сохраняем, иначе - выводим ошибки
    protected function update() {
        $this->setUpdate();

        $arg = func_get_args();
        $save = get($arg, 0) === 'save';

//        $response = $this->ajax();
        // проверяем формы модулей на ошибки
        $isError = false;
        $modulAll = $this->modulAll();
        while (list(, $modul) = each($modulAll)) {
            $modul->updateIsError($isError);
            if ($isError)
                break;
        }

        $js = '';
        if (!$isError) {
            // обновляем данные
            reset($modulAll);
            while (list(, $modul) = each($modulAll)) {
                $modul->updateOk();
            }

            //создание папки для записи
            if ($this->command()->isNewRecord()) {
                cmfWysiwyng::addRecord($this->wysiwyngPath(), $this->id()->get());
            }



            // сохранение - возвращется на страницу списка
            if ($save and $this->id()->get()) {
                $this->runUpdateData();
                $this->ajax()->redirect($this->url()->getCatalog());
            }

            // если создали новый элемент перегружаем страницу
            if ($this->command()->isNewRecord()) {
                $this->runUpdateData();
                $this->ajax()->redirect($this->url()->getSubmit());
            }

            // открываем страницу сново
            if ($this->command()->isReload()) {
                $this->ajax()->script('cmfAjaxOpenUrl("' . $this->url()->getSubmit() . '");');
            }

            // отрисовываем страницу сново
            if ($this->command()->isNewView()) {
                return;
            }

            reset($modulAll);
            while (list($name, $modul) = each($modulAll)) {
                $modul->run();
                $js .= $modul->getJsSetData();

                $file = $modul->getListFile();
                foreach ($file as $f) {
                    $js .= $this->getJsFile($name, $f);
                }
            }
        } else {
            // выводим пользователю ошибки форм
            reset($modulAll);
            while (list(, $modul) = each($modulAll)) {
                $modul->updateError();
                $modul->getUpdateError();
                $js .= $modul->getJsSetData(false);
            }
        }
        $this->ajax()->script($js);
    }

    // загрузка файлов
    protected function getFileId($name, $element) {
        return $this->getName() . '_' . $this->id()->get() . '_' . $name . $element;
    }

    public function getImage($name, $element, $option = array(), $text = '(Добавить)') {
        $option['isImage'] = 1;
        $this->getFile($name, $element, $option, $text);
    }

    public function getImageView($name, $element, $option = array(), $text = '(Добавить)') {
        $option['isImageView'] = 1;
        $this->getImage($name, $element, $option, $text);
    }

    public function getFile($name, $element, $option = array(), $text = '(Добавить)') {
        $modul = $this->modul($name);
        if (is_null($modul))
            return '';
        if (!$modul->form()->get()->is($element))
            return '';

        $fileId = $this->getFileId($name, $element);
        $jsModul = $this->html()->jsName();
        $form = $modul->form()->get();

        view_edit::getFile($this->id()->get(), $jsModul, $fileId, $form, $name, $element, $option, $text);
    }

    public function getJsFile($name, $element) {
        $modul = $this->modul($name);
        if (is_null($modul))
            return '';
        if (!$modul->form()->get()->is($element))
            return '';

        $fileId = $this->getFileId($name, $element);
        $jsModul = $this->html()->ajaxJsName();
        $form = $modul->form()->get();

        $option = cInput::post()->get($fileId . '_option');
        if ($option)
            $option = unserialize($option);

        view_edit::getJsFile($this->id()->get(), $jsModul, $fileId, $form, $name, $element, $option);
    }

    protected function deleteFile($name, $element, $id) {
        if (!$this->id()->get()) {
            $this->id()->set($id);
        }
        $model = $this->modul($name);
        if (is_null($model)) {
            exit;
        }

        $model->deleteFile($element);
        $this->ajax()->html($this->getFileId($name, $element), '');
    }

    /*
      // добавление строк в список
      protected function &getNewLineData() {
      $data = array();
      $modulAll = $this->getModulAll();
      while (list($name, $modul) = each($modulAll)) {
      $data[$name] = $modul->getNewLineData();
      }
      return $data;
      }

      protected function saveLineOk($formName) {
      $modulAll = $this->getModulAll();
      while (list($name, $modul) = each($modulAll)) {
      $this->id()->set(0);
      $modul->saveLineOk(get($formName, $name));
      }
      }

      // функции для управления папками для визуального редактора
      public function getWysiwyngIsRecord($id) {
      return (bool) $this->getModul()->getDb()->getDataId($id);
      }

      public function getWysiwyngPath() {
      $wysiwing = new cmfWysiwyng();
      return $wysiwing->getRecordPath(get_class($this));
      }
     */
}

?>
