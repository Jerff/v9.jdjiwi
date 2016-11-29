<?php

cDebug::destroy();

//$r = cRegister::request();
if(!$productId = (int)cInput::get()->get('id')) exit;

if($notice = cmfCache::getParam('ajaxProductNotice', $productId)) {
} else {
    $sql = cRegister::sql();
    $product = $sql->placeholder("SELECT p.*, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount, s.name AS secName, s.basket AS secBasket, s.isUri AS secUrl, s.path AS secPath, s.root AS secRoot, b.uri AS bUri, b.name AS bName
                                              FROM ?t p LEFT JOIN ?t s ON(p.section=s.id) LEFT JOIN ?t b ON(p.brand=b.id)
                                              WHERE p.id=? AND p.visible='yes' AND s.isVisible='yes'", db_product, db_section, db_brand, $productId)
    					->fetchAssoc();
    if(!$product) exit;
    if(empty($product['count']) and $product['isOrder']==='no') exit;

    $productParam = cConvert::unserialize($product['param']);
    $productDump = cConvert::unserialize($product['paramDump']);
    $path = cConvert::pathToArray($product['secPath']);
    $path[$product['section']] = $product['section'];

    /* параметры */
    list(, $paramList, $basketId, $basket) = cmfParam::getNotice($path, $product['secBasket']);
    $productParam['articul'] = $product['articul'];
    $productParam['brand'] = $product['bName'];
    $notice = cmfParam::generateNotice($productParam, $paramList, $product, $basketId, $productDump);

    cmfCache::setParam('ajaxProductNotice', $productId, $notice, 'product,paramList');
}

$i = 0;
foreach($notice as $k=>$v) { ?>
    <? if($i++) { ?><br /><? } ?>
    <?=$k ?>: <?=$v ?>
<? } ?>