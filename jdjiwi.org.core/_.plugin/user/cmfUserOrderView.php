<?php


cLoader::library('ajax/cControllerAjax');
class cmfUserOrderView extends cControllerAjax {

	function __construct($orderId, $formUrl=null, $name=null, $func=null) {
        $this->set('$orderId', $orderId);
		if(!$name)		$name = 'orderView';
		if(!$formUrl)	$formUrl = cAjaxUrl .'/user/orderView/?orderId='. $orderId;
		if(!$func)		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func);
	}

	static public function isView($orderId) {
        return cSession::is('$orderId'. $orderId);
	}
	static public function setView($orderId) {
        return cSession::set('$orderId'. $orderId, 1);
	}

	protected function init() {
        $form = $this->form()->get();
        $form->add('email',           new cFormText(array('!empty', 'name'=>'E-mail', 'email', 'min'=>4, 'max'=>100)));
	}

	public function run1() {
		$userData = $this->processing();
        $orderId = $this->get('$orderId');
        $email = $userData['email'];

        $status = cmfOrder::getStatusList(0, 1, 2);
        $order = cRegister::sql()->placeholder("SELECT EMS, data, deliveryDesc FROM ?t WHERE id=? AND status ?@ AND `delete`='no'", db_basket, $orderId, array_keys($status))
								->fetchAssoc();
		if(!$order) {
		    exit;
		}
		list(, $email2, $userData, $userAdress, $userSubscribe) = unserialize($order['data']);
		if(!empty($order['EMS'])) {
            $EMS = $order['EMS'];
        }
		if($email2!==$email) {
		    $this->form()->get()->setError('email', 'Неправильный адрес');
			$this->runEnd(true);
		    exit;
		}
        if(!empty($order['deliveryDesc']))
            $deliveryDesc = cConvert::unserialize($order['deliveryDesc']);

		ob_start();
        ?>
                                    <div class="tittle tittle-top">Данные заказа</div>
                                    <? foreach($userData as $k=>$v) { ?>
                                        <b><?=$k ?>: <i><?=is_null($v) ? '' : ''. $v ?></i></b>
                                    <? } ?>
                                    <div class="tittle">Доставка</div>
                                    <? foreach($userAdress as $k=>$v) { ?>
                                        <b><?=$k ?>: <i><?=is_null($v) ? '' : ''. nl2br($v) ?></i></b>
                                    <? } ?>
                                    <? if(isset($EMS)) { ?>
                                        <b>Номер отправления EMS: <i><?=$EMS ?></i></b>
                                    <? } ?>
                                    <? if($userSubscribe) { ?>
                                    <div class="tittle">Подписка на новости</div>
                                    <? foreach($userSubscribe as $k=>$v) { ?>
                                        <b><?=$k ?><?=is_null($v) ? '' : ' '. $v ?></b>
                                    <? }} ?>

                                    <? if(isset($deliveryDesc)) { ?>
                                        <div class="tittle tittle-top">
                                            <br><a id="contentDeliveryLink" href="#contentDelivery">Статус доставки</a>
                                        </div>
                                        <div class="cHide"><div id="contentDelivery" class="item-block">
                                            <h1>Статус доставки</h1>
                                            <?=$deliveryDesc['content'] ?>
                                        </div></div>
                                        <script type="text/javascript">
                                            $("#contentDeliveryLink").fancybox({
                                        });
                                        function Wind(ID) {
                                            var link = "http://www.russianpost.ru/resp_engine.aspx?Path=RP/SERVISE/RU/Home/postuslug/SearchOPS/SearchOPSPortalExtended&NEWSID=" + ID;
                                            window.open(link, "", "menubar=no,scrollbars=1,resizable=no,width=750, height=500");
                                        }
                                        </script>
                                    <? } ?>

        <?
        cAjax::get()->html('.confidentialData', ob_get_clean());
        cmfUserOrderView::setView($orderId);
	}

}

?>