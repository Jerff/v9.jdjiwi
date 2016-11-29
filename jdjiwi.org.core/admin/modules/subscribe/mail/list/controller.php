<?php


class subscribe_mail_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'subscribe_mail_list_modul');

		// url
		$this->url()->setSubmit('/admin/subscribe/mail/');
		$this->url()->setEdit('/admin/subscribe/mail/edit/');
		$this->access()->writeAdd('setActive|changeFilter');
		$this->url()->set('user', '/admin/user/');
	}

	public function changeFilter() {
        $email = trim(cInput::post()->get('email'));
        $opt = array();
        $opt['email'] = $email ? urlencode($email) : null;
		$this->ajax()->redirect($this->url()->getSubmit($opt));
	}

	public function getUserUrl($user) {
		$opt['id'] = $user;
		return $this->url()->get('user', $opt);
	}
	public function listUser() {
		$res = $this->sql()->placeholder("SELECT id, name, family FROM ?t WHERE id IN(SELECT user FROM ?t WHERE id ?@ GROUP BY `user`)", db_user, db_subscribe_mail, $this->getDataRecord())
								->fetchAssocAll('id');
		foreach($res as $k=>$v) {
		    $res[$k] = cmfUser::generateName($v);
		}
		return $res;
	}

	public function filterType() {
		$filter = model_subscribe::typeList();
		$filter[0]['name'] = 'Все';
		return parent::abstractFilter($filter, 'type', 'end');
	}

	public function delete($id) {
		$id = cLoader::initModul('subscribe_mail_edit_controller')->delete($id);
		return parent::delete($id);
	}


    public function getStatus($status) {
        if(!$this->id()->get()) return;
        return $this->getStatusHtml($status);
    }

    public function getStatusHtml($status) {
        $id = $this->id()->get();
        ob_start();
        switch($status) {
            case 'no':
                ?>Неактивирован<br><?=cmfAdminView::onclickType1("if(confirm('Активировать?')) modul.postAjax('setActive', $id);", 'Активировать') ?>
                <?
                break;

            case 'yes':
                ?>Активирован<?
                break;

            default:
                break;
        }
        return ob_get_clean();
    }

    protected function setActive($id) {
        $this->id()->set($id);
        $this->accessShop();
        cLoader::initModul('subscribe_mail_edit_db')->setActive();
        $this->ajax()->html('#status'. $id, $this->getStatusHtml('yes'));
    }

}

?>