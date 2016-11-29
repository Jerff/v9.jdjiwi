<?php


class subscribe_mail_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'subscribe_mail_edit_modul');

		// url
		$this->url()->setSubmit('/admin/subscribe/mail/edit/');
		$this->url()->setCatalog('/admin/subscribe/mail/');
		$this->url()->set('user', '/admin/user/');
		$this->access()->writeAdd('setActive');
	}

    public function delete($id) {
        $this->id()->set($id);
        $data = $this->modul()->getDb()->runData();
        if(!empty($data['user']) and !empty($data['type'])) {
            cmfUserModel::saveParam(array($data['type']=>'no'), $data['user']);
        }
		$id = parent::delete($id);
		$this->sql()->del(db_subscribe_status, array('mail'=>$id));
		return $id;
	}

	public function getUserUrl($user) {
		$opt['id'] = $user;
		return $this->url()->get('user', $opt);
	}

	public function listUser() {
		$res = $this->sql()->placeholder("SELECT id, name, family FROM ?t WHERE id IN(SELECT user FROM ?t WHERE id=? GROUP BY `user`)", db_user, db_subscribe_mail, $this->id()->get())
								->fetchAssocAll('id');
		foreach($res as $k=>$v) {
		    $res[$k] = cmfUser::generateName($v);
		}
		return $res;
	}

    public function deleteUser($id) {
		$where = array('user'=>$id);
		$this->delete($this->getListId($where));
	}

    public function getStatus($status) {
        return $this->getStatusHtml($status);
    }

    public function getStatusHtml($status) {
        ob_start();
        switch($status) {
            case 'no':
                ?>Неактивирован<br><?=cmfAdminView::onclickType1("if(confirm('Активировать?')) modul.postAjax('setActive');", 'Активировать') ?>
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

    protected function setActive() {
        $this->modul()->getDB()->setActive();;
        $this->ajax()->html('#status', $this->getStatusHtml('yes'));
    }

}

?>