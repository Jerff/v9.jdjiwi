<?php


class subscribe_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'subscribe_list_modul');

		// url
		$this->url()->setSubmit('/admin/subscribe/');
		$this->url()->setEdit('/admin/subscribe/edit/');
		$this->access()->writeAdd('mailStart|mailContinue|mailReset|mailStop');

	}

	public function delete($id) {
		$id = cLoader::initModul('subscribe_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function filterType() {
		$filter = array();
        $filter['all']['name'] = 'Обычная рассылка';
		foreach(model_subscribe::typeList() as $k=>$v) {
            $filter[$k]['name'] = 'Обычная рассылка -> '. $v['name'];
        }

		$filter['user']['name'] = 'Произвольная рассылка';
		$filter[0]['name'] = 'Все';
		return parent::abstractFilter($filter, 'type', 'end');
	}

    public function getStatus($status) {
        if(!$this->id()->get()) return;
        return $this->getStatusHtml($status);
    }

    public function getStatusHtml($status) {
        $id = $this->id()->get();
        ob_start();
        switch($status) {
            case 'неактивна':
                ?>Неактивна<br><?=cmfAdminView::onclickType1("if(confirm('Активировать?')) modul.postAjax('mailStart', $id);", 'Активировать') ?>
                <?
                break;

            case 'активна':
                ?>Активна<br><?=cmfAdminView::onclickType1("if(confirm('Остановить?')) modul.postAjax('mailStop', $id);", 'Остановить') ?>
                <?
                break;

            case 'остановлена':
                ?>Остановлена
                <br><?=cmfAdminView::onclickType1("if(confirm('Продолжить?')) modul.postAjax('mailContinue', $id);", 'Продолжить') ?>
                <br><?=cmfAdminView::onclickType1("if(confirm('Начать заново?')) modul.postAjax('mailReset', $id);", 'Начать заново') ?>
                <?
                break;

            case 'закончена':
                ?>Закончена
                <br><?=cmfAdminView::onclickType1("if(confirm('Повторить?')) modul.postAjax('mailReset', $id);", 'Повторить') ?>
                <?
                break;

            default:
                break;
        }
        return ob_get_clean();
    }

    protected function mailStart($id) {
        $this->id()->set($id);
        cLoader::initModul('subscribe_edit_db')->mailStart();
        $this->ajax()->html('#status'. $id, $this->getStatusHtml('активна'));
    }

    protected function mailContinue($id) {
        $this->id()->set($id);
        cLoader::initModul('subscribe_edit_db')->mailContinue();
        $this->ajax()->html('#status'. $id, $this->getStatusHtml('активна'));
    }

    protected function mailReset($id) {
        $this->id()->set($id);
        cLoader::initModul('subscribe_edit_db')->mailReset();
        $this->ajax()->html('#status'. $id, $this->getStatusHtml('активна'));
	}

	protected function mailStop($id) {
        $this->id()->set($id);
        cLoader::initModul('subscribe_edit_db')->mailStop();
        $this->ajax()->html('#status'. $id, $this->getStatusHtml('остановлена'));
	}

}

?>