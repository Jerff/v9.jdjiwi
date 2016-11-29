<?php


class subscribe_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'subscribe_edit_modul');

		// url
		$this->url()->setSubmit('/admin/subscribe/edit/');
		$this->url()->setCatalog('/admin/subscribe/');
		$this->access()->writeAdd('mailStart|mailContinue|mailReset|mailStop|mailTest');
	}

    public function delete($id) {
		$id = parent::delete($id);
        cLoader::initModul('subscribe_history_view_controller')->deleteSubscribe($id);
		$this->sql()->del(db_subscribe_status, array('subscribe'=>$id));
		return $id;
	}

    public function getStatus($status) {
        return $this->getStatusHtml($status);
    }

    public function getStatusHtml($status) {
        ob_start();
        switch($status) {
            case 'неактивна':
                ?>Неактивна<br><?=cmfAdminView::onclickType1("if(confirm('Активировать?')) modul.postAjax('mailStart');", 'Активировать') ?>
                <?
                break;

            case 'активна':
                ?>Активна<br><?=cmfAdminView::onclickType1("if(confirm('Остановить?')) modul.postAjax('mailStop');", 'Остановить') ?>
                <?
                break;

            case 'остановлена':
                ?>Остановлена
                <br><?=cmfAdminView::onclickType1("if(confirm('Продолжить?')) modul.postAjax('mailContinue');", 'Продолжить') ?>
                <br><?=cmfAdminView::onclickType1("if(confirm('Начать заново?')) modul.postAjax('mailReset');", 'Начать заново') ?>
                <?
                break;

            case 'закончена':
                ?>Закончена
                <br><?=cmfAdminView::onclickType1("if(confirm('Повторить?')) modul.postAjax('mailReset');", 'Повторить') ?>
                <?
                break;

            default:
                break;
        }
        return ob_get_clean();
    }

    protected function mailIsRun() {
        $this->update();
        $this->run();
        $data = $this->modul()->getData();
        if($data['type']==='user' and empty($data['email'])) {
            $this->ajax()->alert('Заполните адреса получателей');
            exit;
        }
    }

    protected function mailStart() {
        $this->mailIsRun();
        $this->modul()->getDB()->mailStart();;
        $this->ajax()->html('#status', $this->getStatusHtml('активна'));
    }

    protected function mailContinue() {
        $this->mailIsRun();
        $this->modul()->getDB()->mailContinue();;
        $this->ajax()->html('#status', $this->getStatusHtml('активна'));
    }

    protected function mailReset() {
        $this->mailIsRun();
        $this->modul()->getDB()->mailReset();;
        $this->ajax()->html('#status', $this->getStatusHtml('активна'));
	}

	protected function mailStop() {
        $this->modul()->getDB()->mailStop();;
        $this->ajax()->html('#status', $this->getStatusHtml('остановлена'));
	}

	protected function mailTest() {
        $email = cSettings::read('subscribe', 'email');
        $send = $this->modul()->form()->get()->handler();

        $cmfMail = new cmfMail();
        $cmfMail->sendHTML($email, $send['header'], $send['content']);

        $this->ajax()->alert('Письмо отправлено');
	}

}

?>