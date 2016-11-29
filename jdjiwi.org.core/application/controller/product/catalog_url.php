<?php


if(cPages::param()->get(3)==='search') return '/section/search/';
$row = cRegister::sql()->placeholder("SELECT page, id AS section, brand, product FROM ?t WHERE url=?", db_content_url, cPages::param()->get(5))
							->fetchRow();
if(!$row) return 404;
list($page, $section, $brand, $product) = $row;

cGlobal::set('$sectionId', $section);
cGlobal::set('$brandId', $brand);
cGlobal::set('$productId', $product);
switch($page) {
	case '/section/':
		return '/section/';

	case '/brand/':
		if($section) {
    		return '/brand/section/';
        } else {
        	return '/brand/';
        }

	case '/product/':
		if(cPages::param()->count()==5) {
		    return '/product/';
		} else {
		    return 404;
		}
}


cGlobal::set('$infoUri', cString::substr(cPages::param()->get(1), 1, -1));
return $page;

?>