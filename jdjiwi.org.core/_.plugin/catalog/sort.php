<?php


function cmfCatalogFilter($sort, $asc, $limit=null, $_limit=array()) {
	if(!in_array($sort, array('name', 'price'))) {
		$sort = 'name';
	}
	if(!in_array($asc, array('asc', 'desc'))) {
		$asc = 'asc';
	}

	if($_limit and !in_array($limit, $_limit)) {
		$limit = $_limit[0];
	}
	return array($sort, $asc, $limit, $_limit);
}


function &cmfCatalogLimitToView($itemUrl, $name, $sort, $asc, $limit, $_limit) {
	$_limitUrl = array();
	foreach($_limit as $k) {
	    $_limitUrl[$k] = array('url'=>$itemUrl . cmfGeneratePageUrl($name, $sort, $asc, $k));
	}
	$_limitUrl[$limit]['sel'] = 1;
	return $_limitUrl;
}

function cmfCatalogSortToView($itemUrl, $name, $sort, $asc, $limit=false) {
	$_sort = array(
	'name'=>'наименованию',
	'price'=>'цене'
	);

	$_sortUrl = array(
	'name'=>	array(	'asc'=>		$itemUrl . cmfGeneratePageUrl($name, 'name', 'asc', $limit),
						'desc'=>	$itemUrl . cmfGeneratePageUrl($name, 'name', 'desc', $limit)),
	'price'=>	array(	'asc'=>		$itemUrl . cmfGeneratePageUrl($name, 'price', 'asc', $limit),
						'desc'=>	$itemUrl . cmfGeneratePageUrl($name, 'price', 'desc', $limit))
	);
	$_sortUrl[$sort][$asc] = null;
	return array($_sort, $_sortUrl);
}


?>