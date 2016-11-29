<?php


class basket_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'basket_list_modul');

		// url
		$this->url()->setSubmit('/admin/basket/');
		$this->url()->setEdit('/admin/basket/edit/');
		$this->url()->set('print', '/admin/basket/printer/');
		$this->url()->set('user', '/admin/user/edit/');
		$this->access()->writeAdd('setType|changePay');
		$this->access()->readAdd('searchOrder');
	}

	protected function getLimitAll() {
		return array(5, 10, 15, 'all');
	}

	public function filterType() {
		$filter = array();
		$filter[1]['name'] = 'Заказ выполняется';
		$filter[2]['name'] = 'Заказ закончен';
		$filter[0]['name'] = 'Заказ отменен';
		$filter['all']['name'] = 'Все';
		return parent::abstractFilter($filter, 'status');
	}

	public function delete($id) {
		$id = cLoader::initModul('basket_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function getPrintUrl($opt=null) {
		$opt['id'] = $this->id()->get();
		return $this->url()->get('print', $opt);
	}
	public function getUserUrl($user) {
		$opt['id'] = $user;
		return $this->url()->get('user', $opt);
	}

	public function listUser() {
		$res = $this->sql()->placeholder("SELECT id, name, family FROM ?t WHERE id IN(SELECT user FROM ?t WHERE id ?@ GROUP BY `user`)", db_user, db_basket, $this->getDataRecord())
								->fetchAssocAll('id');
		foreach($res as $k=>$v) {
		    $res[$k] = cmfUser::generateName($v);
		}
		return $res;
	}

	protected function deleteView($dataId) {
		if(!cAjax::is()) return;
		$jsModul = $this->html()->ajaxJsName();
		foreach((array)$dataId as $id) {
			$this->ajax()->script("{$jsModul}.deleteLine('". $this->getHtmlIdDel($id) ."');
                                             {$jsModul}.deleteLine('data_". $this->getHtmlIdDel($id) ."');");
		}
	}


	protected function searchOrder() {
        $number = cInput::post()->get('number');
        $is = cLoader::initModul('basket_edit_db')->getDataWhere(array('id'=>$number, 'AND', 'delete'=>'no'));
        if($is) {
            $this->ajax()->redirect($this->url()->getEdit(array('id'=>$number)));
        } else {
            $this->ajax()->script("cmf.style.show('#searchError'); $('#searchError').html('Заказ не найден')");
        }
	}

	public function getStatus($data) {
		return cLoader::initModul('basket_edit_controller')->getStatus($data, $this->html()->jsName());
	}
	public function getCommand($id, $data) {
		return cLoader::initModul('basket_edit_controller')->getCommand($id, $data, $this->html()->jsName());
	}

	public function changePay($id) {
		cmfControllerOrder::changePay($id);
		$this->command()->reloadView();
    }
	public function setType($id, $status) {
		if(false!==$stop = cmfControllerOrder::setType($id, $status)) {
			$this->command()->reloadView();
		}
	}

}

?>