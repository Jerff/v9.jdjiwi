<?php


class user_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_user;
	}

	public function save($send) {
		$id = cmfUserModel::save($send, $this->id()->get());
		$this->saveSetId($id);
		$this->saveEnd($id, $send);
		$this->update()->set($id, $send);
	}

	public function updateData($list, $send) {
	    if(isset($send['email'])) {


            list($subscribe, $shop) = cLoader::initModul('user_param_edit_db')->getFeildsRecord(array('subscribe', 'shop'), $this->id()->get());
            list($email) = cLoader::initModul('user_edit_db')->getFeildsRecord(array('email'), $this->id()->get());
            if($subscribe==='yes') {
                cmfSubscribe::addUser($email, $shop, $this->id()->get());
            } else {
                cmfSubscribe::delUser($this->id()->get());
            }
	    }
	}

	public function getUserStat($id) {
		$stat = array();
		$stat['Заказы']  = $this->sql()->placeholder("SELECT count(`id`) FROM ?t WHERE user=?", db_basket, $id)
												->fetchRow(0);
		$row = $this->sql()->placeholder("SELECT `userPay`, discount FROM ?t WHERE id=?", db_user_data, $id)
									->fetchAssoc();
		$stat['Оплачено'] = $row['userPay'] ? $row['userPay'] .'руб.' : 'нет';
		$stat['Скидка'] = $row['discount']==1 ? 'нет' : (1-$row['discount'])*100 .'%';
		return $stat;
	}

}

?>