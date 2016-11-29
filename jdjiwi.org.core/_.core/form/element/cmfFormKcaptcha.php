<?php


class cmfFormKcaptcha extends cFormText {

	protected function init($o) {
		$this->setFilter('cmfNotEmpty');
		parent::init($o);
	}

	private function getKcaptchaId() {
		return preg_replace('~([^a-z])~is', '', $this->getId());
	}
	private function getKcaptchaName() {
		return sha1($this->getKcaptchaId());
	}

	public function processing($data, $old, $upload) {
		$value2 = cSession::get('kcaptcha', $this->getKcaptchaName());
		if(empty($value2) or get($data, $this->getId())!=$value2) {
			cmfFormError::set("поле заполнено неправильно");
		}
		return null;
	}

	public function free() {
		cSession::del('kcaptcha', $this->getKcaptchaName());
	}

	public function html($param, $style='') {
		return '<input type="text" name="'. ($name=$this->getId()) .'" id="'. $name .'" '. $style .' />';
	}

	public function image($style='') {
		return '<img id="'. $this->getId() .'image" src="/library/kcaptcha/'. $this->getKcaptchaId(). '/" border="0" '. $style .' onclick="'. $this->jsReloadImage() .'">';
	}

	protected function jsUpdateValue() {
        return '';
	}

	public function jsReloadImage() {
		return "$('#". $this->getId() ."image').attr('src', '/library/kcaptcha/". $this->getKcaptchaId() ."/'+ Math.random() +'/');";
	}

}

?>