<?php

$sectionId = cGlobal::get('$sectionId');
$brandId = cGlobal::get('$brandId');
$productId = cGlobal::get('$productId');

cLoader::library('form/cmfFeedback');
if($product = cmfCache::getParam('product', array($sectionId, $brandId, $productId))) {
	list(list($product, $path, $productParam, $productPrice),
	     $_menu,
	     list($image, $imageHeight),
	     list($notice, $basket, $price, $isDiscount),
	     $color, $attach,
	     $feedback, $feedbackContent,
	     $isOrderContent, $isNotOrder) = $product;
} else {
    $sql = cRegister::sql();
    $product = $sql->placeholder("SELECT p.*, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount, s.name AS secName, s.basket AS secBasket, s.isUri AS secUrl, s.path AS secPath, s.root AS secRoot, b.uri AS bUri, b.name AS bName
                                              FROM ?t p LEFT JOIN ?t s ON(p.section=s.id) LEFT JOIN ?t b ON(p.brand=b.id)
                                              WHERE s.id=? AND p.id=? ". ($brandId ? "AND p.brand='$brandId'" : '') ." AND p.visible='yes' AND s.isVisible='yes'", db_product, db_section, db_brand, $sectionId, $productId)
    					->fetchAssoc();
    if(!$product) return 404;
    if(empty($product['count']) and $product['isOrder']==='no') $product['isNotOrder'] = true;
    $sectionUri = $product['secUrl'];
    $productParam = cConvert::unserialize($product['param']);
    $productPrice = cConvert::unserialize($product['paramPrice']);
    $productDump = cConvert::unserialize($product['paramDump']);
    if($product['colorAll']) {
    	$productParam['color'] = cConvert::pathToArray($product['colorAll']);
    }
    $path = cConvert::pathToArray($product['secPath']);
    $path[$sectionId] = $sectionId;


    $_menu = $sql->placeholder("SELECT name, isUri FROM ?t WHERE id ?@ ORDER BY FIELD('id', ?s)", db_section, $path, implode(',', $path))
    				                    ->fetchAssocAll();


    /* изображения */
    $res =  $sql->placeholder("SELECT id, name, color, image_main AS main, image_product AS product, image_small AS small FROM ?t WHERE product=? AND visible='yes' AND image IS NOT NULL ORDER BY pos DESC", db_product_image, $productId)
    				->fetchAssocAll();
    $image = array();
    $imageMain = $imageProduct = false;
    foreach($res as $v) {
        if(!$imageMain) {
            $imageMain = cBaseImgUrl . path_product . $v['main'];
            $imageProduct = cBaseImgUrl . path_product . $v['product'];
        }
        $image[$v['id']] = array('title'=>cString::specialchars($v['name'] ? $v['name'] : $product['name']),
                	             'color'=>$v['color'],
                                 'main'=>cBaseImgUrl . path_product . $v['main'],
                                 'product'=>cBaseImgUrl . path_product . $v['product'],
                                 'small'=>cBaseImgUrl . path_product . $v['small']);
    }
    $imageHeight = ceil(count($image)/4);


    /* параметры */
    list(, $paramList, $basketId, $basket) = cmfParam::getNotice($path, $product['secBasket']);
    //$productParam['articul'] = $product['articul'];
    $productParam['brand'] = $product['bName'];
    if($product['discount']!=1) {
        $productParam['discount'] = $product['discount']*100;
    }
    $notice = cmfParam::generateNotice($productParam, $paramList, $product, $basketId, $productDump);

    $price = cmfParam::generateBasket($basket, $product, $basketId, $productParam, $productPrice, $paramList, $productDump);
    $isDiscount = $product['isDiscount'];

    /* цвета */
    $color = $sql->placeholder("SELECT id, color FROM ?t WHERE visible='yes' ORDER BY name", db_color)
    				->fetchRowAll(0, 1);
    foreach($color as $k=>$v) {
    	if(!isset($productParam['color'][$k])) {
            unset($color[$k]);
    	}
    }

    /* attach */
    $res = $sql->placeholder("SELECT  p.id, p.name, u.url, p.price, p.image_small AS image FROM ?t p LEFT JOIN ?t u ON(u.product=p.id) WHERE u.product=p.id AND p.id IN(SELECT product1 FROM ?t WHERE product2=?) AND p.id!=? AND ". ($brandId ? "u.brand!='0'" : "u.brand='0'") ." AND p.count>'0' ORDER BY name", db_product, db_product_url, db_product_attach, $productId, $productId)
                    ->fetchAssocAll();
    $attach = array();
    foreach($res as $row) {
    	$attach[$row['id']] = array('title'=>cString::specialchars($row['name']),
                	                'image'=>cBaseImgUrl . path_product . $row['image'],
                	                'url'=>cUrl::get('/product/', $row['url']));
    }
    $attachHeight = ceil(count($attach)/3);


    $feedback = new cmfFeedback($productId);
    $feedbackContent = $sql->placeholder("SELECT content  FROM ?t WHERE name='Товары: Задать вопрос'", db_content_static)
    			          ->fetchRow(0);
    $feedbackContent = strip_tags($feedbackContent);

    $isOrderContent = $sql->placeholder("SELECT content  FROM ?t WHERE name='Товары: товар под заказ'", db_content_static)
    			          ->fetchRow(0);

    $isNotOrder = $sql->placeholder("SELECT content  FROM ?t WHERE name='Товары: товар недоступен для заказа'", db_content_static)
    			          ->fetchRow(0);

    cmfCache::setParam('product', array($sectionId, $brandId, $productId), array(array($product, $path, $productParam, $productPrice),
                                                                        	     $_menu,
                                                                        	     array($image, $imageHeight),
                                                                        	     array($notice, $basket, $price, $isDiscount),
                                                                        	     $color, $attach,
                                                                        	     $feedback, $feedbackContent,
                                                                        	     $isOrderContent, $isNotOrder), 'section,brand,product,paramList');
}

    $this->assing('name', $product['name']);
    $this->assing('articul', $product['articul']);
    $this->assing('content', $product['notice']);
    $this->assing2('title', cString::specialchars($product['name']));
    $this->assing('productId', $productId);

    $this->assing('feedback',        $feedback);
    $this->assing('feedbackForm',    $feedback->form()->get());
    $this->assing('feedbackContent', $feedbackContent);

    cGlobal::set('$searchUri', $product['secUrl']);
    cmfMenu::setSelect('$rootId', $product['secRoot'] ? $product['secRoot'] : $sectionId);
    cGlobal::set('$rootId', $product['secRoot'] ? $product['secRoot'] : $sectionId);
    cGlobal::set('$sectionList', $path);
    cSeo::set('title', $product['title']);
    cSeo::set('keywords', $product['keywords']);
    cSeo::set('description', $product['description']);


    /* меню */
    foreach($_menu as $row) {
        cmfMenu::add($row['name'], cUrl::get('/section/', $row['isUri']));
    }
    if($brandId) {
        cmfMenu::add($product['bName'], cUrl::get('/section/', $sectionUri .'/'. $product['bUri']));
    }
    cmfMenu::add($product['name']);

    $this->assing('image', $image);
    $this->assing('imageHeight', $imageHeight);
    $this->assing('notice', $notice);
    $this->assing2('sizeUrl', cUrl::get('/info/size/'));
    $this->assing('basket', $basket);
    $this->assing2('basketName', mb_strtolower($basket['name'], cCharset));
    $this->assing('price', $price);
    $this->assing2('notPrice', (int)empty($product['price1']));
    $this->assing('isDiscount', $isDiscount);

    if(!$isDiscount) {
        if($productPrice) {
            $this->assing2('priceOld', min($productPrice));
        } else {
            $this->assing('priceOld', $product['price1']);
        }

        $this->assing2('priceDicount', 100-$product['discount']*100);
    }

    if($color) {
    	$this->assing('color', $color);
    }
    if($attach) {
    	$this->assing('attach', $attach);
    }

    if(isset($basket['isOrder'])) {
        $this->assing2('isOrder', $isOrderContent);
    }

    if(isset($product['isNotOrder'])) {
        header("HTTP/1.0 404 Not Found");
        $this->assing2('isNotOrder', $isNotOrder);
    }

?>