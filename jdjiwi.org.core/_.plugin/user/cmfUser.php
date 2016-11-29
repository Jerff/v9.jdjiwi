<?php

cLoader::library('basket/cmfBasket');
cLoader::library('user/model/cmfDriverUser');
cLoader::library('user/authorization/cmfAuth');
class cmfUser extends cmfAuth {

	protected function getName() {
	    return 'sessionUser';
	}

    // формы� ������ ������
    protected function getFields() {
		return array('id', 'login', 'email', 'name', 'family', 'email');
	}
    // формы� �������������� ������
	public function getFieldsParam() {
		return array('discount', 'userBasket', 'userPay');
	}

	// формы�
	public function filterIsUser(){
		if($this->is()) return;
		cRedirect(cUrl::get('/user/enter/'));
	}
	public function filterNoUser(){
		if(!$this->is()) return;
		if(isset($_GET['fancybox'])) {
		    ?>
            <script type="text/javascript">
            cmf.redirect('<?=cUrl::get('/user/') ?>');
            </script>
		    <?
            exit;
		} else {
		    cRedirect(cUrl::get('/user/'));
		}
	}


	protected function sessionUpdate() {
        $data = $this->getData();
		cCookie::set($this->getName() .'Name', $data['name']);
		cCookie::set($this->getName() .'Email', $data['email']);
		cmfCacheUser::setDiscount($data['discount']);
		cmfCacheUser::setPay($data['userPay']);
		if(!empty($data['userBasket'])) {
			$basket = new cmfBasket();
			$basket->loadUser($data['userBasket']);
		}
	}


	public function updateBasket($basket) {
		if($this->getId())
		cRegister::sql()->add(db_user_data, array('userBasket'=>$basket), $this->getId());
	}


	protected function sessionRemove() {
		parent::sessionRemove();
		cCookie::del($this->getName() .'Name');
		cmfCacheUser::setDiscount(0);
	}

    // формы�������
	public function logOut(){
		parent::logOut();
		$basket = new cmfBasket();
		$basket->disable();
	}

	public static function generateName($send){
		$name = array();
        if(isset($send['Имя'])) {
            if(!empty($send['Имя'])) $name[] = $send['Имя'];
            if(!empty($send['Фамилия'])) $name[] = $send['Фамилия'];
        } else {
            if(!empty($send['name'])) $name[] = $send['name'];
            if(!empty($send['family'])) $name[] = $send['family'];
        }
		return implode(' ', $name);
	}

	public static function getUserId() {
		return cGlobal::get('$userId');
	}

}

?>