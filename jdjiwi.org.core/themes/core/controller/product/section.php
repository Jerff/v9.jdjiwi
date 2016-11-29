<?php


$sectionId = cGlobal::get('$sectionId');
$brandId = cGlobal::get('$brandId');

$itemUri = cPages::param()->get(5);
$nameUri = cPages::param()->get(7);
$paramUri = cPages::param()->get(9);
$typeUri = cPages::param()->get(11);
$sortUri = cPages::param()->get(13);
$page = (int)cPages::param()->get(15);
if(!$page) $page = 1;
$limit = cPages::param()->get(17);
$_itemUri = array();
if($itemUri) $_itemUri['section'] = $itemUri;
if(isset($_GET['form'])) {
    if(!empty($_POST['searchName'])) {
        $name = urldecode(trim($_POST['searchName']));
        if($name!=='Поиск...' and $name!=='Артикул...') {
            $_itemUri['name'] = $name;
            cRedirect(cUrl::get('/section/search/', cmfProductUrl::generate($_itemUri)));
        }
    }
    if(empty($itemUri)) {
        cRedirect(cUrl::get('/search/'));
    } else {
        if($paramUri) $_itemUri['param'] = $paramUri;
        if($sortUri) $_itemUri['sort'] = $sortUri;
        if($typeUri) $_itemUri['type'] = $typeUri;
        if($page!=1) $_itemUri['page'] = $page;
        $_itemUri['limit'] = $limit;
        cRedirect(cUrl::get('/section/', cmfProductUrl::generate($_itemUri)));
    }
}


if($paramUri) {
	$_itemUri['param'] =  $paramUri;
	$searchId = explode('-', $paramUri);
} else {
	$searchId = array();
}


$isSearch = cPages::isMain('/section/search/') && (!empty($nameUri) || !empty($itemUri));
if($isSearch) {
	if(empty($nameUri)) {
        $nameUri = substr($itemUri, 5);
        $itemUri = '';
        unset($_itemUri['section']);
    }
	cGlobal::set('$searchName', htmlspecialchars(urldecode($nameUri)));
	$sectionPage = '/section/search/';
    $sectionDefault = '/section/';
} else {
    $sectionPage = $sectionDefault = '/section/';
}

if($paramUri) $_itemUri['param'] = $paramUri;
if($nameUri) $_itemUri['name'] = $nameUri;
if($sortUri) $_itemUri['sort'] = $sortUri;
if($typeUri) $_itemUri['type'] = $typeUri;
if($page!=1) $_itemUri['page'] = $page;
if($limit) $_itemUri['limit'] = $limit;
cGlobal::set('$_itemUri', $_itemUri);


$isNew = $isSale = false;
switch($typeUri) {
    case 'sale': $isSale = true; break;
    case 'new': $isNew = true; break;
}
cGlobal::set('$isSale', $isSale);
cGlobal::set('$isNew', $isNew);

$_limit = explode('-', cSettings::get('catalog', 'productList'));
$_limit = array_combine($_limit, $_limit);
$limitStart = cSettings::get('catalog', 'productLimit');
if(!isset($_limit[$limit])) {
    if($limit==='all') {
        $limit = 200;
        $isAll = true;
	} else {
		$limit = $limitStart;
	}
}



$sql = cRegister::sql();
$sort = $where = $menu = array();
$where['visible'] = 'yes';
if($isSearch) {
    $name = urldecode($nameUri);
	$where[] = 'AND';
 	if(strlen($name)>4) {
        $where[] = $sql->getQuery("MATCH (`content`) AGAINST (? IN BOOLEAN MODE)", $name);
	} else {
		$where[] = $sql->getQuery("`content` LIKE '%?s%'", $name);
	}

    if(!empty($_itemUri['section'])) {
        $row = cRegister::sql()->placeholder("SELECT page, id AS section, brand, product FROM ?t WHERE url=?", db_content_url, $_itemUri['section'])
                                ->fetchRow();
        if(!$row) return 404;
        list(, $sectionId, $brandId) = $row;
    }
}


switch($sortUri) {
    default:
    case 'new':
        $sort['created'] = 'DESC';
        $sortUri = 'new';
        break;
    case 'old':
        $sort['created'] = 'ASC';
        $sortUri = 'old';
        break;
    case 'desc':
        $sort['price'] = 'DESC';
        $sortUri = 'desc';
        break;
    case 'asc':
        $sort['price'] = 'ASC';
        $sortUri = 'asc';
        break;
}



if($sectionId) {
    if($section = cmfCache::getParam('section', $sectionId)) {
    	list($section, $sectionUri, $path, $sectionList, $isMain, $paramFilter, $paramList, $_article) = $section;
    } else {

        $section = $sql->placeholder("SELECT id, parent, root, path, name, isUri, title, keywords, description FROM ?t WHERE id=? AND visible='yes'", db_section, $sectionId)
        				->fetchAssoc();
        $path = cConvert::pathToArray($section['path']);
        $menu = array();
        if($path) {
            $res = $sql->placeholder("SELECT name, isUri FROM ?t WHERE id ?@ ORDER BY FIELD('id', ?s)", db_section, $path, implode(',', $path))
            				                    ->fetchAssocAll();
            foreach($res as $row) {
                $menu[] = array('name'=>$row['name'],
                                'uri'=>$row['isUri']);
            }
        }
        $sectionUri = $section['isUri'];
        $sectionList = $sql->placeholder("SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%'", db_section, $sectionId, $sectionId)
                                    ->fetchRowAll(0, 0);

        $path[$sectionId] = $sectionId;

		$paramList = $paramFilter = array();
		$isMain = (isset($section) and !$section['parent'] and count($_itemUri)==1);
		if(!$isMain) {
			list($paramFilter, $paramList) = cmfParam::getFilter($path);
		}

        $_article = array();
        $res = $sql->placeholder("SELECT id, header, date, image, image_title, notice, uri FROM ?t WHERE section=? AND visible='yes' ORDER BY isMain, date DESC LIMIT 0, ?i", db_article, $sectionId/*array_merge($sectionList, $path)*/, cSettings::get('article', 'articleSection'))
    							->fetchAssocAll();
        foreach($res as $row) {
            $_article[]  = array('date'=>cmfView::date(strtotime($row['date'])),
                                'header'=>$row['header'],
                                'title'=>cString::specialchars(empty($row['image_title']) ? $row['header'] : $row['image_title']),
                                'image'=>$row['image'] ? path_article . $row['image'] : null,
                                'notice'=>$row['notice'],
                                'url'=>cUrl::get('/article/', $row['uri']));
        }

        cmfCache::setParam('section', $sectionId, array($section, $sectionUri, $path, $sectionList, $isMain, $paramFilter, $paramList, $_article), 'sectionList,paramList');
    }


    foreach($menu as $k=>$v) {
        if(isset($v['uri'])) {
            cmfMenu::add($v['name'], cUrl::get($sectionDefault, $v['uri']));;
        }
    }

    if($brandId or $isSale or $isNew or count($_itemUri)>1) {
        cmfMenu::add($section['name'], cUrl::get($sectionDefault, $section['isUri']));
    } else {
        cmfMenu::add($section['name']);
    }

    if(!$isSearch){
        $where[] = 'AND';
        $where['section'] = $sectionList;
    }

    if($_article) {
        $this->assing('_article', $_article);
    }

    cSeo::set('title', $section['title']);
    cSeo::set('keywords', $section['keywords']);
    cSeo::set('description', $section['description']);

    cGlobal::set('$secPath', $path);
    cGlobal::set('$sectionName', $section['name']);
    cGlobal::set('$searchUri', $sectionUri);

    cmfMenu::setSelect('$rootId', $section['root'] ? $section['root'] : $sectionId);
    cGlobal::set('$rootId', $section['root'] ? $section['root'] : $sectionId);
    cGlobal::set('$sectionUri', $section['isUri']);
    cGlobal::set('$sectionList', $sectionList);

} else {
	if($paramList = cmfCache::get('section')) {
    } else {
    	list($paramFilter, $paramList) = cmfParam::getFilter();
    	cmfCache::set('section', $paramList, 'paramList');
    }
}


if(!$brandId and !empty($isMain)) {
	return '/section/main/';
}


if($brandId) {
    $brand = $sql->placeholder("SELECT id, name, uri, title, keywords, description FROM ?t WHERE id=? AND visible='yes'", db_brand, $brandId)
                   ->fetchAssoc();
    cSeo::set('brand', $brand['name']);
    if(!$sectionId) {
        cSeo::set('title', $brand['title']);
        cSeo::set('keywords', $brand['keywords']);
        cSeo::set('description', $brand['description']);
    }
    cGlobal::set('$brandUri', $brand['uri']);

    if($isNew or $isSale) {
        cmfMenu::add($brand['name'], $sectionId ? cUrl::get('/brand/section/', $section['isUri'], $brand['uri']) :
                                                  cUrl::get('/brand/', $brand['uri']));
    } else {
        cmfMenu::add($brand['name']);
    }
    $where[] = 'AND';
    $where['brand'] = $brandId;
}

if($isNew) {
    $where[] = 'AND';
    $where[] = "`created`>'". (time() - cSettings::get('catalog', 'novelty') *24*60*60) ."'";
    cmfMenu::add('Новинки');
} else if($isSale) {
    $where[] = 'AND';
    $where['type'] = 'sale';
    cmfMenu::add('Sale');
}

if($search = cmfCache::getParam('section', array($sectionId, $brandId, $searchId))) {
	list($search, $paramList, $_discount, $where, $priceId) = $search;
} else {
    $search = array();
    $priceId = 0;
    if($paramFilter) {
	    foreach($paramFilter as $k1=>$v1) {
	        $search[$k1] = array();
	        foreach($paramFilter as $k2=>$v2) {
	        	if($k1!=$k2 and !empty($searchId[$k2])) {
	                if(!empty($search[$k1])) {
	                	$search[$k1][] = 'AND';
	                }
	                $search[$k1][$v2] = $searchId[$k2] * get2($paramList, $v2, 'rateId', 1);
	            }
	        }
	    }

	    $id = 0;
	    $priceWhere = array();
	    foreach($paramList as $k=>$row) {
	        if($k==='price') {
	            $w = $where;
	            if(!empty($search[$id])) {
	                $w[] = 'AND';
	        	    $w = array_merge($w, $search[$id]);
	            }
	            list($min, $max) = $row = $sql->placeholder("SELECT min(price), max(price) FROM ?t WHERE ?w", db_search, $w)
	                                                ->fetchRow();
	            if($min==$max and empty($min)) continue;
                $step = ceil(($max-$min)/cSettings::get('param', 'priceStep')/500)*500;
	            $row['value'][0] = "Цена";
	            if($min+$step<$max) {
	                for($price=$min; $price<=$max; $price+=$step) {
	                    $item = ceil($price/500)*500;
	                    $next = $item+$step;
	                    if(($price+(1.5*$step))>$max) {
	                        $row['value'][$item] = "от {$item} руб. и выше";
	                        $priceWhere[$item] = "`price`>='$item'";
	                        break;
	                    }
	                    if($price==$min) {
	                    	$row['value'][$item] = "до {$next} руб.";
	                    	$priceWhere[$item] = "`price`<='$next'";
	                    } else {
	                        $row['value'][$item] = "от {$item} до {$next} руб.";
	                        $priceWhere[$item] = "(`price`>='$item' AND `price`<='$next')";
	                    }
	                }

		            $query = $sep = '';
		            $w[] = 'AND';
		            foreach($priceWhere as $k2=>$v2) {
		            	$query .= $sep . $sql->getQuery("(SELECT {$k2} AS id FROM ?t WHERE ?w LIMIT 0, 1)", db_search, array_merge($w, array($v2)));
		            	$sep = " \nUNION ";
		            }
		            $res = $sql->query($query)->fetchRowAll(0);
		            foreach($priceWhere as $k2=>$v2) {
		            	if(!isset($res[$k2])) {
		            		unset($priceWhere[$k2], $row['value'][$k2]);
		            	}
		            }

	            } elseif($min==$max) {
	            	$row['value'][$min] = "{$min} руб.";
	            	$priceWhere[$min] = "(`price`='$min')";
	            } else {
	                $price = ceil($min + ($max-$min)/2);
	                $row['value'][$min] = "до {$price} руб.";
	                $row['value'][$price] = "от {$price} руб. и выше";
	                $priceWhere[$min] = "(`price`<='$min')";
	                $priceWhere[$price] = "(`price`>='$price')";
	            }
                $row['style'] = 'class="filterPrice"';

	            $priceId = get($searchId, $id);
	            if($priceId and !isset($priceWhere[$priceId])) {
	            	$new = 0;
	            	foreach($priceWhere as $k2=>$v2) {
	                	if(!$new or $priceId>=$k2) {
	                		$new = $k2;
	                	}
	            	}
	                $priceWhere[$priceId] = $priceWhere[$new];
	                $priceId = $new;
	            }
	            $paramList[$k] = $row;

	        }
	        $id++;
	    }

	    $query = $sep = '';
	    $id = 0;
	    foreach($paramList as $k=>$row) {
	        $l = '';
	        if($k==='price') {
	        } else {
	        	$w = $where;
	        	if(!empty($search[$id])) {
	            	if(isset($search[$id]['price']) && isset($priceWhere[$search[$id]['price']])) {
	            		$new = array();
	            		foreach($search[$id] as $k2=>$v2) {
	            			if($k2==='price') {
	                            $new[] = $priceWhere[$v2];
	            			} else if(is_numeric($k2)) {
	            			    $new[] = $v2;
	            			} else {
	                            $new[$k2] = $v2;
	            			}
	            		}
	                    $search[$id] = $new;
	            	}
	                $w[] = 'AND';
	        	    $w = array_merge($w, $search[$id]);
	            }
	            $param = $paramFilter[$id];
	        	$rateDb = get($row, 'rateDb');
	        	$query .= $sep . $sql->getQuery("(SELECT {$id} AS param, {$param}$rateDb AS value FROM ?t WHERE ?w GROUP BY {$param})", db_search, $w);
	            $sep = " \nUNION ";
	        }
	        $id++;
	    }

        $res =  $sql->query($query)->fetchRowAll(0, 1, 1);
	    $id = 0;
	    foreach($paramList as $k=>$row) {
	        if($k==='price') {
	        } else {
	        	if(isset($res[$id])) {
	        		$v = array(0=>$row['name']);
	        		foreach($row['value'] as $k2=>$v2) {
	        			if(isset($res[$id][$k2])) {
	        				$v[$k2] = $v2;
	        			}
	        		}
	        		$paramList[$k]['value'] = $v;
	        	} else {
	        		$paramList[$k]['value'] = array();
	        	}
	        }
	        $id++;
	    }

	    foreach($paramFilter as $k=>$v) {
	        if(!empty($searchId[$k])) {
	            $where[] = 'AND';
	            if($v==='price') {
	                if(isset($priceWhere[$searchId[$k]])) {
	                	$where[] = $priceWhere[$searchId[$k]];
	                }
	            } elseif($v==='discount') {
	            	$where[$v] = $searchId[$k]/100;
	            } else {
	            	$where[$v] = $searchId[$k];
	            }
	        }
	    }
    }
    $_discount = $sql->placeholder("SELECT name, CONCAT(?, image) FROM ?t WHERE visible='yes'", path_discount, db_param_discount)
                   ->fetchRowAll(0, 1);

    cmfCache::setParam('section', array($sectionId, $brandId, $searchId), array($search, $paramList, $_discount, $where, $priceId), 'paramList');
}


$uriWhere = array('brand'=>0);
if($isSearch) {
	$name = urldecode($itemUri);
 	if(strlen($name)>4) {
        $product = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id,
                                        MATCH (`content`) AGAINST (? IN BOOLEAN MODE) as REL FROM ?t
                                        WHERE ?w
                                        GROUP BY id ORDER BY Rel DESC, ?o LIMIT ?i, ?i", $name, db_search, $where, $sort, ($page-1)*$limit, $limit)
                        ->fetchRowAll(0, 0);
	}
}
if(!isset($product)) {
    $product = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id FROM ?t WHERE ?w GROUP BY id ORDER BY ?o LIMIT ?i, ?i", db_search, $where, $sort, ($page-1)*$limit, $limit)
                    ->fetchRowAll(0, 0);
}
if($product) {
    $count = $sql->getFoundRows();
    $res = $sql->placeholder("SELECT p.id, u.url, p.name, b.name AS bName, p.price, IF(p.price1, p.price1, p.price2) AS priceOld, p.image_section, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount, p.discount, IF(p.count='0' AND p.isOrder='yes', '1', '0') AS isOrder FROM ?t p LEFT JOIN ?t u ON(u.product=p.id) LEFT JOIN ?t b ON(b.id=p.brand) WHERE b.id=p.brand AND u.product=p.id AND p.id ?@ AND ?w:u ORDER BY FIELD(p.`id`, ?s)", db_product, db_product_url, db_brand, $product, $uriWhere, implode(',', $product))
    				    ->fetchAssocAll();
    $_product = array();
    foreach($res as $row) {
        $_product[$row['id']] = array('name'=>$row['name'],
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

    if($_product) {
        $this->assing('_product', $_product);
        $this->assing2('productCount', ceil(count($_product)/3)*3);
        $_page_url = cmfPagination::generate($page, $count, $limit, cSettings::get('catalog', 'productPage'),
            create_function('&$page, $k, $v, $_itemUri', '
            	$page[$k]["url"] = cmfGetUrl("'. $sectionPage .'", cmfProductUrl::replace($_itemUri, "page", $k));'), $_itemUri);
        if($_page_url) $this->assing('_page_url', $_page_url);
    }
}


$_itemUri['page'] = 1;
$id = 0;
$paramFilter = array();
foreach($paramList as $k=>$v) {
	$_uri = $_itemUri;
	$_uri['param'] = $searchId;
    $paramFilter[$k]['style'] = $v['style'];
	$sel = $k==='price' ? $priceId : get($searchId, $id);
	foreach($v['value'] as $k2=>$v2) {
        $paramFilter[$k]['value'][$k2] = array('name'=>$v2,
                                               'sel'=>$sel==$k2? true : null,
                                               'url'=>cUrl::get($sectionPage, cmfProductUrl::replace($_uri, 'param', $id, $k2, 'page', 1)));
	}
	$id++;
}
//pre($where, $paramFilter);
$this->assing2('filter', $paramFilter);

$list = array();
foreach(array('asc'=>'По возрастанию цены',
              'desc'=>'По убыванию цены',
              'new'=>'Новые сверху',
              'old'=>'Старые сверху') as $k=>$v) {
    $list[$k] = array('name'=>$v,
                      'url'=>cUrl::get($sectionPage, cmfProductUrl::replace($_itemUri, 'sort', $k, 'page', 1)));
}
if(isset($list[$sortUri])) {
	$list[$sortUri]['sel'] = true;
}
$this->assing2('sort', $list);


$list = array();
foreach($_limit as $v) {
    $list[$v] = array('name'=>$v,
                      'url'=>cUrl::get($sectionPage, cmfProductUrl::replace($_itemUri, 'limit', $limitStart==$v ? null : $v)));
}
    $list['all'] = array('name'=>'Все',
                         'url'=>cUrl::get($sectionPage, cmfProductUrl::replace($_itemUri, 'limit', 'all', 'page', 1)));
if(isset($list[$limit])) {
	$list[$limit]['sel'] = true;
} elseif(isset($isAll)) {
    $list['all']['sel'] = true;
}
$this->assing2('limit', $list);


$this->assing2('searchUrl', $url = cUrl::get($sectionDefault, cmfProductUrl::replace($_itemUri, 'page', 1)));
$this->assing2('defaultName', 'Артикул...');
$this->assing2('searchName', cGlobal::get('$searchName', 'Артикул...'));
cGlobal::set('$searchUrl', $url)

?>