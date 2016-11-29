<?php


if($_menu = cmfCache::get('_footer.index')) {
	list($_menu, $email, $network, $_news, $_article, $_product, $_brand, $_photo, $copyright, $counters, $subscribeYes, $cartNotice, $cart) = $_menu;
} else {

	$sql = cRegister::sql();
	$_menu = cmfMenu::getFooter();
    $email = cSettings::get('showcase', 'email');
    $network = str_replace('%itemUrl%', urlencode(cInput::url()->adress()), cSettings::get('showcase', 'network'));

	$res = $sql->placeholder("SELECT id, header, date, notice, uri FROM ?t WHERE visible='yes' ORDER BY isMain, date DESC LIMIT 0, ?i", db_news, cSettings::get('news', 'newsMain'))
							->fetchAssocAll();
    $_news = $_article = $_product = $_brand = $_photo = array();
    foreach($res as $row) {
        $_news[]  = array('date'=>cmfView::date(strtotime($row['date'])),
                          'header'=>$row['header'],
                          'notice'=>$row['notice'],
                          'url'=>cUrl::get('/news/', $row['uri']));
    }

	$res = $sql->placeholder("SELECT id, header, date, image, image_title, notice, uri FROM ?t WHERE visible='yes' ORDER BY isMain, date DESC LIMIT 0, ?i", db_article, cSettings::get('article', 'articleMain'))
							->fetchAssocAll();
    foreach($res as $row) {
        $_article[]  = array('date'=>cmfView::date(strtotime($row['date'])),
                            'header'=>$row['header'],
                            'title'=>cString::specialchars(empty($row['image_title']) ? $row['header'] : $row['image_title']),
                            'image'=>$row['image'] ? path_article . $row['image'] : null,
                            'notice'=>$row['notice'],
                            'url'=>cUrl::get('/article/', $row['uri']));
    }

	$res = $sql->placeholder("SELECT id, header, date, image, image_title, uri FROM ?t WHERE visible='yes' ORDER BY isMain, date DESC LIMIT 0, ?i", db_photo, cSettings::get('photo', 'photoMain'))
							->fetchAssocAll();
    foreach($res as $row) {
        $_photo[]  = array('date'=>cmfView::date(strtotime($row['date'])),
                            'header'=>$row['header'],
                            'title'=>cString::specialchars(empty($row['image_title']) ? $row['header'] : $row['image_title']),
                            'image'=>$row['image'] ? path_photo . $row['image'] : null,
                            'url'=>cUrl::get('/photo/', $row['uri']));
    }

	$copyright = nl2br(cSettings::get('seo', 'copyright'));
	$counters = $sql->placeholder("SELECT id, counters FROM ?t WHERE main='yes' AND visible='yes' ORDER BY pos ", db_seo_counters)
							->fetchRowAll(0, 1);
	$counters = implode(' ', $counters);

    $_discount = $sql->placeholder("SELECT name, CONCAT(?, image) FROM ?t WHERE visible='yes'", path_discount, db_param_discount)
                   ->fetchRowAll(0, 1);

	$res = $sql->placeholder("(SELECT 'new' AS type, p.id, u.url, p.name, b.name AS bName, p.price, IF(p.price1, p.price1, p.price2) AS priceOld, p.image_section, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount, p.discount, IF(p.count='0' AND p.isOrder='yes', '1', '0') AS isOrder FROM ?t p LEFT JOIN ?t u ON(u.product=p.id AND u.brand='0') LEFT JOIN ?t b ON(b.id=p.brand) WHERE b.id=p.brand AND b.visible='yes' AND p.visible='yes' AND (p.count>'0' OR p.isOrder='yes') ORDER BY p.created DESC LIMIT 0, ?i)
                    	      UNION
                    	      (SELECT 'view' AS type, p.id, u.url, p.name, b.name AS bName, p.price, IF(p.price1, p.price1, p.price2) AS priceOld, p.image_section, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount, p.discount, IF(p.count='0' AND p.isOrder='yes', '1', '0') AS isOrder FROM ?t p LEFT JOIN ?t u ON(u.product=p.id AND u.brand='0') LEFT JOIN ?t b ON(b.id=p.brand) WHERE b.id=p.brand AND b.visible='yes' AND p.visible='yes' AND (p.count>'0' OR p.isOrder='yes') ORDER BY p.view DESC, p.created DESC LIMIT 0, ?i)",
                        	    db_product, db_product_url, db_brand, cSettings::get('showcase', 'productLimit'),
                        	    db_product, db_product_url, db_brand, cSettings::get('showcase', 'productLimit'))
				->fetchAssocAll();
    foreach($res as $row) {
        $_product[$row['type']][$row['id']] = array('name'=>$row['name'],
                                                    'title'=>cString::specialchars($row['name']),
                                                    'bName'=>$row['bName'],
                                                    'price'=>$row['price'],
                                                    'priceOld'=>$row['priceOld'],
                                                    'isDiscount'=>$row['isDiscount'],
                                                    'discount'=>get($_discount, 100-$row['discount']*100),
                                                    'isOrder'=>$row['isOrder'],
                                                    'image'=>cBaseImgUrl . path_product . $row['image_section'],
                                                    'url'=>cUrl::get('/product/', $row['url']));
    }
	$res = $sql->placeholder("SELECT b.id, b.uri, b.name, i.isNewProduct FROM ?t b LEFT JOIN ?t i ON(b.id=i.brand and i.section='0') WHERE b.visible='yes' AND i.isMenu='yes' ORDER BY b.name", db_brand, db_section_is_brand)
							->fetchAssocAll();
    foreach($res as $row) {
        $t = trim($row['name']);
        if(is_numeric($t{0})) {
            $t = '123';
        } else {
            $t = mb_strtoupper(mb_substr($t, 0, 1, cCharset));
        }
        $_brand[$t][ ] = array('name'=>$row['name'],
                               'isNew'=>(bool)$row['isNewProduct'],
                               'url'=>cUrl::get('/brand/', $row['uri']));
    }
    foreach($_brand as $t=>$list) {
        $_brand[$t] = array_chunk($list, ceil(count($list)/4));
    }


    cLoader::library('subscribe/cSubscribeYes');
    $subscribeYes = new cSubscribeYes('leftSubscribeYes');

	$cart = array();
	$cart['notice'] = cSettings::get('payment', 'notice');
	$cart['payment'] = cSettings::get('payment', 'payment');

	cmfCache::set('_footer.index', array($_menu, $email, $network, $_news, $_article, $_product, $_brand, $_photo, $copyright, $counters, $subscribeYes, $cart), 'menu,seoCounters,subscribe,seoCopyright');
}

$this->assing('_menu', $_menu);
$this->assing('_news', $_news);
$this->assing('_article', $_article);
$this->assing2('articleUrl', cUrl::get('/article/all/'));
$this->assing('_photo', $_photo);
$this->assing2('photoUrl', cUrl::get('/photo/all/'));
$this->assing('email', $email);
$this->assing('network', $network);

$this->assing('_product', $_product);
$this->assing('_brand', $_brand);

$this->assing('copyright', $copyright);
$this->assing('counters', $counters);

$this->assing('subscribeYes', $subscribeYes);
$this->assing('form',         $subscribeYes->form()->get());

$this->assing('cart',   $cart);

?>