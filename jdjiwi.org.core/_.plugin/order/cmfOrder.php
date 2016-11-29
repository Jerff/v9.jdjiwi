<?php


class cmfOrder {


    static public function initPrice($_product, $_basket=null, $pricePay=null) {
        if(!$_basket) {
            $_basket = array();
            $isNewOrder = true;
        }
        $_colorAll = cRegister::sql()->placeholder("SELECT id, name, color FROM ?t WHERE visible='yes' ORDER BY name", db_color)
                                    ->fetchAssocAll('id');
        $res = cRegister::sql()->placeholder("SELECT p.id, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount,
                                                                p.price, p.price1, p.discount, p.paramPrice, p.count, p.isOrder, p.paramDump,
                                                                p.colorAll AS color,
                                                                u.url, b.name AS bName,
                                                                p.articul, CONCAT(s.path, '[', s.id, ']') AS path, p.name, p.image_section AS image
                                                                FROM ?t p LEFT JOIN ?t u ON(u.product=p.id) LEFT JOIN ?t s ON(p.section=s.id) LEFT JOIN ?t b ON(p.brand=b.id)
                                                                WHERE u.product=p.id AND p.id ?@ AND p.visible='yes' AND b.visible='yes'
                                                                ORDER BY p.price DESC, p.name", db_product, db_product_url, db_section, db_brand, array_keys($_product))
                                    ->fetchAssocAll('id');
        foreach($res as $id=>$row) {
            list($name, $_paramName) = cmfParam::getPrice($row['path']);
            if(!isset($_basket[$id])) {
                $_basket[$id] = array('articul'=>$row['articul'],
                                      'name'=>$row['name'],
                                      'bName'=>$row['bName'],
                                      'header'=>$name,
                                      'title'=>cString::specialchars($row['name']),
                                      'image'=>cBaseImgUrl . path_product . $row['image'],
                                      'url'=>cUrl::get('/product/', $row['url']),
                                      'isOrder'=>$row['isOrder']);
            }
            $productPrice = cConvert::unserialize($row['paramPrice']);
            $productDump = cConvert::unserialize($row['paramDump']);
            $_price = get2($_basket, $id, 'price', array());
            $_param = get2($_basket, $id, 'param', array());
            $_dump = get2($_basket, $id, 'dump', array());
            $_comment = get2($_basket, $id, 'comment', array());
            $_color = array();

            foreach($_product[$id] as $pId=>$pValue) {
                if(!isset($_price[$pId])) {
                    if(isset($_paramName[$pId])) {
                        if(!$row['price1']) {
                            if(empty($productPrice[$pId])) {
                                continue;
                            }
                            $_price[$pId] = $productPrice[$pId]*$row['discount'];
                        } else {
                            $_price[$pId] = $row['price'];
                        }
                        $_param[$pId] = $_paramName[$pId];
                    } elseif(!$pId) {
                        $_price[$pId] = $row['price'];
                        $_param[$pId] = '';
                    } else {
                        continue;
                    }
                }
                $_dump[$pId] = get($productDump,$pId);
                $_color = array_merge($_color, array_keys($pValue));
            }

            $_basket[$id]['price'] = $_price;
            $_basket[$id]['param'] = $_param;
            $_basket[$id]['count'] = $row['count'];
            $_basket[$id]['dump'] = $_dump;
            $_basket[$id]['discount'] = $row['isDiscount'];

            $_basket[$id]['color'] = array();
            $color = cConvert::pathToArray($row['color']);
            foreach($_colorAll as $k=>$v) if(isset($color[$k]) and array_search($k, $color)) {
                $_basket[$id]['color'][$k] = $v;
            }
        }

        $countAll = $priceAll = $priceDiscount = 0;
        $header = false;
        foreach($_basket as $id=>&$row) {
            if(!isset($_product[$id])) {
                unset($_basket[$id]);
                continue;
            }

            if($header!=='') {
                if(!$header) {
                    $header = $name;
                } elseif($header!=$row['header']) {
                    $header = '';
                }
            }

            $_view = $_count = array();
            $basketPrice = $basketCount = 0;
            $count = $row['count'];
            foreach($_product[$id] as $pId=>$pValue) {
                if(isset($isNewOrder)) {
                    if($pId) {
                        if(empty($row['count']) and $row['isOrder']==='no') continue;
                        $count = get($row['dump'], $pId, 0);
                    } else {
                        if(empty($row['count']) and $row['isOrder']==='no') continue;
                    }
                }

                foreach($pValue as $cId=>$cValue) if(isset($row['price'][$pId])) {
                    if(isset($isNewOrder)) {
                        if($row['isOrder']==='no') {
                            if(empty($count)) continue;
                            if($count>$cValue) {
                                $count -= $cValue;
                                $row['comment'][$pId][$cId] = false;
                            } else {
                                $cValue = $count;
                                $row['comment'][$pId][$cId] = 'на складе '. $cValue .' шт';
                                $count = 0;
                            }
                        } else {
                            if($count>$cValue) {
                                $row['comment'][$pId][$cId] = false;
                            } else {
                                $row['comment'][$pId][$cId] = 'под заказ';
                            }
                        }
                    } else {
                        $new = $cValue - get3($row, 'old', $pId, $cId);
                        if($new>0) {
                            if($row['isOrder']==='no') {
                                if($count>$new) {
                                    $count -= $cValue;
                                } else {
                                    $cValue = get3($row, 'old', $pId, $cId) + $count;
                                    $row['comment'][$pId][$cId] = 'на складе '. $cValue .' шт';
                                }
                            } else {
                                if($count>$new) {
                                } else {
                                    $row['comment'][$pId][$cId] = 'под заказ';
                                }
                            }
                        }
                    }

                    $price = $cValue * $row['price'][$pId];
                    $basketPrice += $price;
                    $_view[$pId][$cId] = cmfPrice::format($price);
                    $_count[$pId][$cId] = $cValue;
                    $countAll += $cValue;
                    if($row['discount']==1) {
                        $priceDiscount += $price;
                    } else {
                        $priceAll += $price;
                    }
                }
            }
            $row['view'] = $_view;
            $row['count'] = $_count;
            $row['old'] = $_count;
            $row['basket'] = cmfPrice::format($basketPrice);
        }

        $price = $priceAll;
        $priceAll = $priceAll + $priceDiscount;

        if(is_null($pricePay)) {
            $pricePay = cmfCacheUser::getPay();
        }
        $discount = cmfDiscount::searchPrice($priceDiscount + $pricePay);
        $discountPay = (int)($priceDiscount*$discount);

        $priceDiscount = $price + $discountPay;
        $discount = $priceAll - $priceDiscount;

        $res = array($header, $_basket, $countAll, $priceAll, $priceDiscount, $discount, $priceDiscount);
        return $res;
	}


    static public function newOrder($data) {
        list(, $_basket) = unserialize($data['product']);
        $paramDump = cRegister::sql()->placeholder("SELECT p.id, p.count, p.paramDump FROM ?t p WHERE p.id ?@ AND p.isOrder='no'", db_product, array_keys($_basket))
                                    ->fetchAssocAll('id');
        foreach($_basket as $id=>$row) if(isset($paramDump[$id])) {
            $dump = cConvert::unserialize($paramDump[$id]['paramDump']);
            $count = $paramDump[$id]['count'];
            foreach($row['count'] as $pId=>$pValue) {
                $cValue = array_sum($pValue);
                if($pId) {
                    $count = get($dump, $pId)-$cValue;
                    if($count<1) {
                        unset($dump[$pId]);
                    } else {
                        $dump[$pId] = $count;
                    }
                } else {
                    $count -= $cValue;
                }
            }
            if($count<0) $count = 0;
            if($dump) {
                $count = array_sum($dump);
            }
            cRegister::sql()->add(db_product, array('count'=>$count, 'paramDump'=>cConvert::serialize($dump)), $id);
        }
        return cRegister::sql()->add(db_basket, $data);
    }


	static public function &getPayTypeList() {
		if(false===($res = cmfCache::get('cmfOrder::getPayTypeList'))) {
			$res = cRegister::sql()->placeholder("SELECT id, name FROM ?t ORDER BY pos", db_basket_pay)
											->fetchRowAll(0, 1);
			cmfCache::set('cmfOrder::getPayTypeList', $res, 'order');
		}
		return $res;
	}


	static public function &getKeyStatusList() {
		$stop = func_get_args();
		if(false===($res = cmfCache::getParam('cmfOrder::getKeyStatusList', $stop))) {

			$res = cRegister::sql()->placeholder("SELECT id FROM ?t WHERE stop ?@", db_basket_status, $stop)
											->fetchRowAll(0, 0);
			cmfCache::setParam('cmfOrder::getKeyStatusList', $stop, $res, 'order');
		}
		return $res;
	}
	static public function &getStatusList() {
		$stop = func_get_args();
		if(false===($res = cmfCache::getParam('cmfOrder::getStatusList', $stop))) {

			$res = cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE stop ?@", db_basket_status, $stop)
											->fetchRowAll(0, 1);
			cmfCache::setParam('cmfOrder::getStatusList', $stop, $res, 'order');
		}
		return $res;
	}
	static public function getStartStatus() {
        return cRegister::sql()->placeholder("SELECT id FROM ?t WHERE `stop`='1' ORDER BY pos LIMIT 0, 1", db_basket_status)
									->fetchRow(0);
	}

}

?>