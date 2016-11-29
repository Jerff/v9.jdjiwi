<?php

class cmfParam {

	static public function getFilter($group=array()) {
        if(!$res = cmfCache::getParam('cmfParam::getFilter', $group)) {
            $res = self::getParam(db_param_group_select, $group);
            cmfCache::setParam('cmfParam::getFilter', $group, $res, 'sectionList,paramList');
        }
        return $res;
    }

	static public function getNotice($group, $basket=0) {
        if(!$res = cmfCache::getParam('cmfParam::getNotice', $group)) {
            if(!$basket) {
                $basket = cRegister::sql()->placeholder("SELECT `basket` FROM ?t WHERE id ?@ AND basket!=0 ORDER BY path DESC LIMIT 0, 1", db_section, $group)
                                                                            ->fetchRow(0);
            }
            $res = self::getParam(db_param_group_notice, $group, $basket);
            cmfCache::setParam('cmfParam::getNotice', $group, $res, 'sectionList,paramList');
        }
        return $res;
    }

	static public function getPrice($path) {
        if(!$res = cGlobal::getParam('cmfParam::getPrice', $path)) {
            if(!$res = cmfCache::getParam('cmfParam::getPrice', $path)) {
                $res = cRegister::sql()->placeholder("SELECT name, value FROM ?t WHERE id=(SELECT `basket` FROM ?t WHERE id ?@ AND basket!=0 ORDER BY path DESC LIMIT 0, 1)", db_param, db_section, cConvert::pathToArray($path))
                                                                                ->fetchRow();
                if($res[1]) $res[1] = unserialize($res[1]);
                cmfCache::setParam('cmfParam::getPrice', $path, $res, 'sectionList,paramList');
            }
            cGlobal::setParam('cmfParam::getPrice', $path, $res);
        }
        return $res;
    }

    static private function getParam($db, $group, $basketId=0) {
        $sql = cRegister::sql();
        array_unshift($group, 0);
        $filterAll = $sql->placeholder("SELECT `group`, param FROM ?t WHERE `group` ?@ AND visible='yes' AND ((CEIL(param)>0 AND param IN(SELECT id FROM ?t WHERE visible='yes')) OR (CEIL(param)=0)) ORDER BY pos", $db, $group, db_param)
        				->fetchRowAll(0, 1, 1);
        $list = $old = array();
        $id = 1;
        foreach($group as $g) {
        	$filter = array();
        	if(isset($filterAll[$g])) {
                foreach($filterAll[$g] as $v) if(!empty($v)) {
                    if(is_numeric($v)) {
                        if(isset($list[$v])) {
                        	$key = $list[$v];
                        } else {
                        	$list[$v] = $key = 'param'. $id++;
                        }
                    } else {
                        $key = $v;
                    }
                    $filter[$v] = $key;
                }
            }
            if($filter) {
                $old = $filter;
            } else {
                $filter = $old;
            }
        }

        $list = $filter;
        if(!empty($filter)) {
            $res = $sql->placeholder("SELECT id, name, notice, style, value, prefix, header FROM ?t WHERE (id ?@ OR id=?) AND visible='yes'", db_param, array_keys($filter), $basketId)
        				->fetchAssocAll();
        	foreach($res as $row) {
        		$list[$row['id']] = array('name'=>$row['name'],
       								      'style'=>empty($row['style']) ? 'class="filterParam"' : $row['style'],
       								      'prefix'=>$row['prefix'],
       								      'value'=>$row['value'] ? unserialize($row['value']) : array());
       	        if($basketId==$row['id']) {
       	            $basket = array('name'=>$row['name'],
       	                            'header'=>$row['header'],
       	                            'prefix'=>$row['prefix'],
       	                            'notice'=>$row['notice'],
       	                            'value'=>$row['value'] ? unserialize($row['value']) : array());
       	        }
        	}

            if(isset($filter['articul'])) {
           		$list['articul'] = array('name'=>'Артикул',
           		                         'style'=>'class="filterArticul"',
           								  'value'=>array());
            }
            if(isset($filter['brand'])) {
           		$list['brand'] = array('name'=>'Производитель',
           		                       'value'=>array());;
            }

            if(isset($filter['color'])) {
           		$list['color'] = array( 'name'=>'Цвет',
           								'style'=>'class="filterMaterial"',
           								'value'=>$sql->placeholder("SELECT id, name FROM ?t WHERE visible='yes' ORDER BY name", db_color)
           									              ->fetchRowAll(0, 1));
            }
            if(isset($filter['discount'])) {
           		$list['discount'] = array('name'=>'Скидка',
           								  'rateDb'=>"*'100'",
           								  'rateId'=>1/100,
           								  'style'=>'class="filterSize"',
           								  'value'=>$sql->placeholder("SELECT 100-name AS name, CONCAT(name, ' %') FROM ?t WHERE visible='yes' ORDER BY name DESC", db_param_discount)
           											      ->fetchRowAll(0, 1));
            }
            if(isset($filter['price'])) {
           		$list['price'] = array('name'=>'Цена',
           		                       'style'=>'class="filterPrice"');
            }

        }
        if(!isset($basket)) {
            $basket = array('name'=>'',
                            'header'=>'',
                            'prefix'=>'',
                            'notice'=>'',
                            'value'=>array());
        }
        return array(array_values($filter), $list, $basketId, $basket);
	}


	static public function &generateNotice($productParam, $paramList, $product, $basketId, $productDump) {
        $isNotOrder = $product['isOrder']==='no';
        $notice = array();
        foreach($paramList as $k=>$v) {
        	$prefix = empty($v['prefix']) ? '' : ' '. $v['prefix'];
        	if(isset($productParam[$k]) and is_array($productParam[$k])) {
        		foreach($v['value'] as $k2=>$v2) {
        			if(!isset($productParam[$k][$k2]) or ($basketId==$k and $isNotOrder and empty($productDump[$k2]))) {
                        unset($v['value'][$k2]);
        			} elseif($prefix) {
                        $v['value'][$k2] .= $prefix;
        			}
        		}
        		if($v['value']) {
        			$notice[$v['name']] = implode(', ', $v['value']);
        		}
        	} else {
        		$v2 = get($v['value'], get($productParam, $k), get($productParam, $k));
        		if($v2) {
        			$notice[$v['name']] = $v2 . $prefix;
        		}
        	}
        }
        return $notice;
    }


	static public function generateBasket(&$basket, $product, $basketId, $productParam, &$productPrice, $paramList, $productDump) {
        if(!$basket) return;
        foreach($productPrice as $k=>$v) {
            if(!isset($paramList[$basketId]['value'][$k])) {
                unset($productPrice[$k]);
            }
        }
        if($isPrice = !$product['price1']) {
            $price = 0;
        } else {
            $price = $product['price'];
        }
        $discount = $product['discount'];
        $prefix = empty($basket['prefix']) ? '' : ' '. $basket['prefix'];
        $basket['count'] = 0;
        $basket['isOrder'] = false;
        foreach($basket['value'] as $k=>$v) {
        	if(!isset($productParam[$basketId][$k]) or (empty($productDump[$k]) and $product['isOrder']==='no')) {
        		unset($basket['value'][$k]);
        	} else {
                $p = 0;
                if($isPrice) {
                	if(!empty($productPrice[$k])) {
                	    $p = $productPrice[$k]*$discount;
                        if(!$price) $price = $p;
                	} else {
                		unset($basket['value'][$k]);
                		continue;
                	}
                }
                if(!isset($basket['isFirstOrder'])) {
                    $basket['isFirstOrder'] = !get($productDump, $k);
                }
        		$basket['count'] += get($productDump, $k);
        		$basket['isOrder'] = $basket['isOrder'] | !get($productDump, $k);
        		$basket['value'][$k] = array('name'=>$v . $prefix,
        		                             'price'=>$p,
        		                             'priceOld'=>get($productPrice, $k),
        		                             'dump'=>get($productDump, $k));
        	}
        }
        if(!isset($basket['isFirstOrder'])) {
            $basket['isFirstOrder'] = $basket['isOrder'] = $product['isOrder']==='yes';
        }
        return $price;
    }

}

?>