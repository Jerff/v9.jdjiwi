<?php

cLoader::library('ajax/cControllerAjax');
cLoader::library('basket/sms/cmfSmsInform');
cLoader::library('limit/cmfLimit');
class cmfCallBack extends cControllerAjax {

	function __construct() {
		$url = cAjaxUrl .'/form/call-back/?';
		$func = 'cmfAjaxSendForm';

		parent::__construct($url, $func);
        $this->loadData();
	}

	protected function init() {
        $form = $this->form()->get();
        $form->setOption('color', '#ff7a7a');
		$form->add('name',		new cFormText(array('name'=>'Контактное лицо', '!empty', 'specialchars')));
        $form->add('cod',		new cFormText(array('errorHide1', '!empty', 'phoneCod', 'min'=>4, 'max'=>4)));
		$form->add('phone',		new cFormText(array('errorHide1', '!empty', 'name'=>'Телефон', 'phonePostPrefix', 'min'=>7, 'max'=>7)));
        $form->add('title',     new cmfFormTextarea(array('name'=>'Вопрос', '!empty', 'specialchars', 'max'=>255)));

        if($this->isCaptcha()) {
            $form->add('captcha',	new cmfFormKcaptcha());
        }
	}

	public function loadData() {
        $this->form()->get()->select(cRegister::getUser()->all);
	}

    public function isCaptcha() {
        return cSettings::get('showcase', 'callBackType')==='sms' and !cRegister::getUser()->is();
    }

    public function isOn() {
        return cSettings::get('showcase', 'callBackOn')==='yes';
    }

	public function run() {
        if(!$this->isOn()) return;
		$data = $this->processing();

        $data['phone'] = $data['cod'] . $data['phone'];
//        $data['phone'] = $data['cod'] .'-'. $data['phone'];
		$data2 = $this->form()->get()->processingName($data);

		$userMail = $data;
        $userMail['data'] = cConvert::arrayView($data2);

        $isSmsLimit = false;
        if(cSettings::get('showcase', 'callBackType')==='sms') {
            $phone = cSettings::get('showcase', 'callBackSms');
            $message = cSettings::get('showcase', 'callBackTemplateSms');

            if(!empty($phone) and !empty($message)) {
                if(cmfLimit::is('showcase', cSettings::get('showcase', 'callBackSmsLimit'))) {
                    cmfLimit::add('showcase');
                    $sms = new cmfSmsInform();
                    $sms->send(cSettings::get('showcase', 'callBackSms'),
                            cSettings::get('showcase', 'callBackTemplateSms'),
                            $userMail);

                } else {
                    $isSmsLimit = true;
                }
            }
        }

        if($isSmsLimit
            or (cSettings::get('showcase', 'callBackType')==='email')
            or (cSettings::get('showcase', 'callBackType')==='sms' and cSettings::get('showcase', 'callBackIsEmail')==='yes')) {

            $cmfMail = new cmfMail();
            $cmfMail->sendType('callback', cSettings::get('showcase', 'callBackTemplateEmail'), $userMail);
        }

        if($this->isCaptcha()) {
            $this->form()->get()->get('captcha')->free();
        }
        cAjax::get()->html($this->getIdForm(), '<b>Заявка&nbsp;отправлена</b>');
	}

	protected function runError($error=null) {
		parent::runError($error);
		cAjax::get()->script("cmf.main.fancybox.reView();");
	}

}

?>