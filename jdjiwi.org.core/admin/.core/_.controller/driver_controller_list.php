<?php

abstract class driver_controller_list extends driver_controller_edit implements Iterator {

    // siteUrl
    public function appUrl() {
        return self::register('siteUrl_core');
    }

    // список id текущих выбранных записей
    private $dataId = null;
    // нужно ли сколько всего записей или нет
    private $isTotal = true;
    // количество всего записей подходящих под условие
    private $total = null;

    function __construct() {
        parent::__construct();

        if ($m = $this->modul()) {
            if ($m->getNewPos()) {
                $this->access()->writeAdd('moveMoveAjax|move1|move2');
            }
        }
        $this->access()->removeAdd('deleteRecord');
        //$this->access()->writeAdd('delete|newLine');
    }

    // работа с урлами ---------------
    public function initRequest() {
        // установка правильного лемита
        $limit = cInput::get()->get('limit', 20);
        $limitAll = $this->getLimitAll();
        if (!in_array($limit, $limitAll)) {
            $limit = current($limitAll);
        }
        cInput::get()->set('limit', $limit);
        parent::initRequest();
    }

    // текущия страница
    protected function getPage() {
        $pg = (int) $this->getFilter('page');
        return $pg > 1 ? $pg : 1;
    }

    // установить & вернуть количество показываемых записей на страницу
    protected function setLimit($limit) {
        $this->setFilter('limit', $limit);
    }

    protected function getLimit() {
        $limit = $this->getFilter('limit');
        if ($limit === 'all')
            return 100;
        else
            return (int) $limit;
    }

    // установка & возврат лимита ссылок страниц на страницу
    protected function getLimitPage() {
        return 20;
    }

    // установка & возврат всех возможных лимитов записей
    protected function getLimitAll() {
        return array(20, 30, 'all');
    }

    // общее количество записей
    // устанвливает & возвращает общее количество записей
    protected function setTotal($total) {
        $this->total = $total;
    }

    public function getTotal() {
        return $this->total;
    }

    // устанвливает & возвращает нужно ли узнавать общее количество записей
    protected function setIsTotal($is = false) {
        $this->isTotal = $is;
    }

    public function getIsTotal() {
        return $this->isTotal;
    }

    // возвращает ссылки на все страницы
    public function &getLinkPage() {
        $total = $this->getTotal();
        if (!$total) {
            $page_link = array();
            return $page_link;
        }

        $page = $this->getPage();
        $page_link = cmfAdminPagination($page, $total, $this->getLimit(), $this->getLimitPage());
        cmfAdminView::pagination($this->url()->getSubmit(), $page, $page_link);
        return $page_link;
    }

    // возвращает возможные limit ссылки
    public function &getLimitUrl() {
        $url = $this->url()->getSubmit(array('page' => 1));
        $limit_url = array();

        foreach ($this->getLimitAll() as $value) {
            $limit_url[$value] = array('name' => ($value === 'all') ? 'Все' : $value,
                'url' => $url . "&limit={$value}");
        }

        $limit_url[$this->getFilter('limit')]['sel'] = 1;
        return $limit_url;
    }

    // устанавливает список id текущих выбранных записей
    protected function setDataId($id) {
        $this->dataId = $id;
    }

    // возвращает список id текущих выбранных записей
    public function getDataId() {
        return $this->dataId;
    }

    // заполнение форм данными из базы
    public function run($id = null) {
        if (is_null($id)) {
            $page = $this->getPage();
            $limit = $this->getLimit();
            $offset = ($page - 1) * $limit;

            if (!$modul = $this->modul())
                return;
            $id = $modul->runList($id, $offset, $limit);
            if ($this->getIsTotal()) {
                $this->setTotal($modul->getTotal());
            } else {
                $this->setTotal(count($id));
            }
            unset($modul);
        } else {

            $this->modul()->runList($id);
            if ($this->getIsTotal()) {
                $this->setTotal(count($id));
            }
        }
        $this->setDataId($id);

        $modulAll = $this->modulAll();
        while (list($name, $modul) = each($modulAll))
            if ($name !== 'main')
                $modul->runList($id);
    }

    // удаление записей
    public function deleteRecord($id) {
        $id = $this->delete($id);
        $this->deleteView($id);
    }

    public function delete($id) {
        return $id;
    }

    protected function deleteList($id) {
        return parent::delete($id);
    }

    protected function deleteView($dataId) {
        $js = '';
        $modul = $this->html()->ajaxJsName();
        foreach ((array) $dataId as $id) {
            $js .= "{$modul}.deleteLine('" . $this->getHtmlIdDel($id) . "');";
        }
        $this->ajax()->script($js);
    }

    // --------------- /заполнение формами данных БД ---------------








    protected function copyInit() {
        $this->command()->reloadView();
    }

    // обновление форм
    protected function updateSortKey(&$list, &$new) {

    }

    protected function update() {
        $this->setUpdate();

        $list = array();
        $new = array();
        foreach ((array) cInput::post()->get($this->getName()) as $key => $id) {
            if ($key === '{0}')
                continue;

            if ($id) {
                $list[$key] = self::filterId($id);
            } else {
                $new[$key] = self::filterId($id);
            }
        }
        if (!count($list) and !count($new))
            return;
        $this->updateSortKey($list, $new);

        $isError = false;
        $modulAll = $this->modulAll();
        while (list(, $modul) = each($modulAll)) {
            if ($list) {
                $modul->updateIsErrorList($list, $isError);
            }
            if ($new) {
                $modul->updateIsErrorList($new, $isError);
            }
            if ($isError)
                break;
        }

        $js = '';
        reset($modulAll);
        while (list($name, $modul) = each($modulAll)) {
            if ($isError) {
                if ($list)
                    $js .= $modul->updateErrorList($list);
                if ($new)
                    $js .= $modul->updateErrorList($new);
            } else {
                if ($list)
                    $modul->updateListOk($list);
                if ($new)
                    $this->newLineOk($new);
            }
        }

        if ($this->command()->isReload()) {
            $this->ajax()->redirect($this->url()->getSubmit());
        }

        if ($this->command()->isNewView()) {
            return;
        }

        if (!$isError) {
            reset($modulAll);
            while (list($name, $modul) = each($modulAll)) {
                $js .= $modul->getJsSetDataList($name, $this, $list);
            }
        }

        $this->ajax()->script($js);
    }

    // удаление файла
    protected function delFile($name, $element, $id = null) {
        if ($id)
            $this->id()->set($id);
        parent::delFile($name, $element);
    }

    // перемещение взад вперед строк, изменение позиции
    public function getPostMove($data) {
        return (view_list::posMove($this->html()->jsName(), $this->getName(), $this->getKey(), $data->pos));
    }

    protected function moveMoveAjax($id, $value) {
        $this->command()->reloadView();
        $this->id()->set($id);
        if ($value < 0)
            $value = 0;
        $this->modul()->save(array('pos' => (int) $value));
    }

    protected function move1($id, $type = null) {
        if ($id2 = $this->modul()->getDb()->move1($id)) {
            list($id2, $pos1, $pos2) = $id2;
            $name = $this->getName();
            $this->ajax()->script("
			$('#{$name}posView{$id}').html('{$pos2}');
			$('#{$name}posView{$id2}').html('{$pos1}');");

            $id2 = $this->getHtmlIdDel($id2);
            $modul = $this->html()->ajaxJsName();
            $id = $this->getHtmlIdDel($id);
            if (!$type)
                $this->ajax()->script("{$modul}.move1('{$id}', '{$id2}');");
            else
                $this->ajax()->script("{$modul}.move2('{$id}', '{$id2}');");
            return true;
        }
        return false;
    }

    protected function move2($id, $type = null) {
        if ($id2 = $this->modul()->getDb()->move2($id)) {
            list($id2, $pos1, $pos2) = $id2;
            $name = $this->getName();
            $this->ajax()->script("
			$('#{$name}posView{$id}').html('{$pos2}');
			$('#{$name}posView{$id2}').html('{$pos1}');");

            $id2 = $this->getHtmlIdDel($id2);
            $modul = $this->html()->ajaxJsName();
            $id = $this->getHtmlIdDel($id);
            if (!$type)
                $this->ajax()->script("{$modul}.move2('{$id}', '{$id2}');");
            else
                $this->ajax()->script("{$modul}.move1('{$id}', '{$id2}');");
            return true;
        }
        return false;
    }

    public function abstractFilter($mFilter, $key, $sort = 'reset') {
        $filterId = urldecode($this->getFilter($key));
        if (!isset($mFilter[$filterId])) {
            if ($sort === 'reset') {
                reset($mFilter);
                $filterId = key($mFilter);
            } elseif ($sort === 'end') {
                end($mFilter);
                $filterId = key($mFilter);
            } else {
                if (isset($mFilter[$sort])) {
                    $filterId = $sort;
                } else {
                    $filterId = key($mFilter);
                }
            }
        }

        $this->setFilter($key, urlencode($filterId));
        if (isset($mFilter[$filterId]))
            $mFilter[$filterId]['sel'] = true;

        $url = $this->url()->getSubmit(array($key => null, 'page' => 1));
        foreach ($mFilter as $id => &$value)
            $value['url'] = $url . '&' . urlencode($key) . '=' . urlencode($id);
        $this->set('filter-' . $key, $mFilter);
        $this->set('filterKey-' . $key, $filterId);
        return $mFilter;
    }

    public function getFilter($key) {
        return $this->get('filter-' . $key);
    }

    public function getFilterId($key) {
        return $this->get('filterKey-' . $key);
    }

    // --------------- вывод html ---------------
    // html - список показанных на странице id
    public function viewListId() {
        return view_list::hideInput($this->getName() . "[" . $this->getIndex() . "]", $this->getKey());
    }

    public function viewDelete() {
        return $this->viewListId() . view_list::viewDelete($this->html()->jsName(), $this->getIndex());
    }

    // html - id строки успользумой для скрытия удаленных данных
    public function getHtmlIdDel($id = null) {
        if (is_null($id))
            $id = $this->getIndex();
        return $this->getName() . '_id_del_' . $id;
    }

    // --------------- вывод html ---------------
    // --------------- интерфейс Iterator к foreach ---------------
    public function rewind() {
        $k = $v = $this->dataId;
        if (!array_search('{0}', $k)) {
            array_unshift($k, '{0}');
            array_unshift($v, 0);
            $this->dataId = array_combine($k, $v);
        } else {
            reset($this->dataId);
        }
    }

    public function &current() {
        $r = new cmfData();
        $id = $this->getKey();
        $index = $this->getIndex();

        $modulAll = $this->modulAll();
        while (list($n, $m) = each($modulAll)) {
            $m->loadFormRunning($id);
            $r->$n = $m->currentList($index, $id);
        }
        return $r;
    }

    protected function getKey() {
        return current($this->dataId);
    }

    public function getIndex() {
        return key($this->dataId);
    }

    public function key() {
        $this->id()->set($id = $this->getKey());
        return $id;
    }

    public function next() {
        return next($this->dataId);
    }

    public function valid() {
        return current($this->dataId) !== false;
    }

    // --------------- интерфейс Iterator к foreach ---------------




    /* new line */
    protected function &getNewLine() {
        if ($this->returnParent()) {
            return cLoader::initModul($this->returnParent());
        } else {
            return $this;
        }
    }

    protected function newLine() {
        $index = 't' . time();
        $id = 0;
        $modul = $this->getNewLine();
        $modul->id()->set($id);
        $data = $modul->getNewLineData();
        unset($modul);

        $this->id()->set($id);
        $result = array();
        $modulAll = $this->modulAll();
        $js = '';
        while (list($name, $modul) = each($modulAll)) {
            $value = get($data, $name);
            $modul->loadForm();
            $js .= $modul->loadFormNewLine($index);
            $form = $modul->newLine($index, $value);

            $file = $form->getListFile();
            foreach ($file as $f) {
                $this->getJsFile($name, $f);
            }

            $result[$name] = $value;
            $js .= $form->jsUpdate();
        }

        $jsModul = $this->html()->ajaxJsName();

        $this->ajax()->script("{$jsModul}.newLine('{$index}', " . json_encode($result) . ");")
                ->script($js);
    }

    protected function newLineOk($new) {
        $controller = $this->getNewLine();

        $modulAll = $this->modulAll();
        foreach ($new as $index => $id) {
            reset($modulAll);
            $formName = array();
            while (list($name, $modul) = each($modulAll)) {
                $formName[$name] = $modul->getNameID($index);
            }
            $controller->saveLineOk($formName);
        }
    }

    protected function deleteFile($name, $element, $id) {
        $this->id()->set($id);
        cAccess::isWrite($this);
        parent::deleteFile($name, $element, $id);
    }

}

?>
