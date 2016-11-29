<?php


$user = cRegister::getUser();
$user->filterIsUser();
$user->reset();
//$user->disable();

$this->assing2('user', $user);
$this->assing2('typeId', $user->main);

$userId = $user->getId();
$sql = cRegister::sql();
$content = cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Личный кабинет: информация'", db_content_static)
							->fetchRow(0);
$this->assing('content', $content);
$this->assing2('infoUrl', cUrl::get('/user/info/'));
$this->assing2('passwordUrl', cUrl::get('/user/info/password/'));
$this->assing2('subscribeUrl', cUrl::get('/user/info/subscribe/'));
$this->assing2('exitUrl', cUrl::get('/user/exit/'));



$page = (int)cPages::param()->get(1);
if(!$page) $page = 1;
$limit = cSettings::get('user', 'mainLimit');
$offset = ($page-1)*$limit;
if($offset>3000) return 404;
$status = cmfOrder::getStatusList(1);
$res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, isPay, orderDate, status, price FROM ?t WHERE user=? AND status ?@ AND `delete`='no' ORDER BY registerDate DESC LIMIT ?i, ?i", db_basket, $userId, array_keys($status), $offset, $limit)
                ->fetchAssocAll();
$_basket = array();
foreach($res as $row) {
    list($countAll, $priceAll, $priceDiscount, $priceDelivery, $discount) = unserialize($row['price']);

    $_basket[$row['id']] = array(   'status'=>$status[$row['status']],
                                    'pay'=>$row['isPay']==='yes' ? 'оплачен' : 'не оплачен',
                                    'url'=>cUrl::get('/user/order/one/', $row['id']),
                                    'price'=>$priceAll,
                                    'date'=>date('d.m.y H:i', strtotime($row['orderDate'])));
}
if($_basket) {
    $this->assing('_basket', $_basket);
    $count = $sql->getFoundRows();

    $_page_url = cmfPagination::generate($page, $count, $limit, cSettings::get('user', 'orderPage'),
        create_function('&$page, $k, $v', '
        	$page[$k]["url"] = $k==1 ? cmfGetUrl("/user/") : cmfGetUrl("/user/page/", array($k));'));
    if($_page_url) $this->assing('_page_url', $_page_url);
}


$count = $sql->placeholder("SELECT count(id) FROM ?t WHERE user=? AND status IN(SELECT id FROM ?t WHERE stop IN(0, 1, 2))", db_basket, $userId, db_basket_status)
				->fetchRow(0);
if($count) {
	$this->assing2('orderUrl', cUrl::get('/user/order/'));
}

?>