<?php


class cmfControllerOrder {


    static public function updatePrice($orderId, $productList, $newList, $deleteList) {
        $sql = cRegister::sql();
        $data = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_basket, $orderId)
                    ->fetchAssoc();
        list(, $_basket) = unserialize($data['product']);
        $isDelivery = $data['isDelivery']==='yes';
        $delivery = $data['deliveryPrice'];
        if($data['user']) {
            $pricePay = $sql->placeholder("SELECT userPay FROM ?t WHERE id=?", db_user_data, $data['user'])
                            ->fetchRow(0);
        } else {
            $pricePay = 0;
        }

        $basket = new cmfBasket(false);
        foreach($productList as $id=>$list) {
            foreach($list as $pId=>$pList) {
                foreach($pList as $cId=>$cValue) {
                    $basket->setProduct($id, $pId, $cId, $cValue);
                }
            }
        }
        if(is_array($deleteList))
        foreach($deleteList as $id=>$list) {
            foreach($list as $pId=>$pList) {
                foreach($pList as $cId=>$cValue) {
                    $basket->delProduct($id, $pId, $cId);
                }
            }
        }
        foreach($newList as $id=>$list) {
            foreach($list as $pId=>$pList) {
                foreach($pList as $cId=>$cValue) {
                    $basket->setProduct($id, $pId, $cId, $cValue);
                }
            }
        }
        list($header, $_basketNew, $countAll, $priceAll, $priceDiscount, $priceDelivery, $discount, $isDelivery, $delivery, $pricePay) = $res = $basket->getBasketProduct($_basket, $pricePay, $isDelivery, $delivery);
        $paramDump = cRegister::sql()->placeholder("SELECT p.id, p.count, p.paramDump FROM ?t p WHERE p.id ?@ AND p.isOrder='no'", db_product, array_merge(array_keys($_basket), array_keys($_basketNew)))
                                          ->fetchAssocAll('id');
        foreach($paramDump as $id=>$row) {
            $dump = cConvert::unserialize($row['paramDump']);
            $count = $row['count'];
            $isEdit = false;
            foreach(array_unique(array_merge(array_keys(get2($_basket, $id, 'count', array())), array_keys(get2($_basketNew, $id, 'count', array())))) as $pId) {
                $cValue = array_sum(get3($_basketNew, $id, 'count', $pId, array())) - array_sum(get3($_basket, $id, 'count', $pId, array()));
//                pre($id, $pId, $cValue, get3($_basketNew, $id, 'count', $pId, array()));
                if(!$cValue) continue;
                if($pId) {
                    $dump[$pId] = get($dump, $pId)-$cValue;
                    if($dump[$pId]<1) {
                        unset($dump[$pId]);
                    }
                }
                $count -= $cValue;
                $isEdit = true;
            }
            if($isEdit) {
                cRegister::sql()->add(db_product, array('count'=>$count, 'paramDump'=>cConvert::serialize($dump)), $id);
            }
        }
        $send = array();
        $send['product'] = serialize(array($header, $_basketNew));
		$send['pay'] = $pricePay;
        $send['price'] = serialize(array($countAll, $priceAll, $priceDiscount, $priceDelivery, $discount));

        $sql->add(db_basket, $send, $orderId);
    }


    static public function setType($id, $status) {
		$sql = cRegister::sql();
		$basket = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_basket, $id)
		                ->fetchAssoc();
        if(!$basket) return false;
        list(, , $userData) = unserialize($basket['data']);

		$stop1 = $sql->placeholder("SELECT stop FROM ?t WHERE id=?", db_basket_status, $basket['status'])
		                ->fetchRow(0);
		if(is_null($stop1)) $stop1 = 1;
		list($stop2, $cnahgePay) = $sql->placeholder("SELECT stop, changePay FROM ?t WHERE id=?", db_basket_status, $status)
		                ->fetchRow();

		$send = array();
        $send['№'] = $send['orderId'] = $id;
        $send['phone'] = $userData['Телефон'];
        $send['name'] = cmfUser::generateName($userData);
        $send['orderUrl'] = cUrl::get('/user/order/one/', $id);


        cmfSmsInform::changeStatus($status, $send);

        $send = array('status'=>$status, 'changeDate'=>date('Y-m-d H:i:s'), 'adminId'=>cRegister::adminId());
		if($stop1==1) {
            switch($stop2) {
                case 0:
                    self::setStatusIsNoOk($id);
                case 1:
                    if($cnahgePay==='yes') {
                        $send['isPay'] = 'yes';
                        self::addOrderPayment($id);
                    }
                    $sql->add(db_basket, $send, $id);
                    break;

                case 2:
                    if($basket['isPay']!=='yes') {
                        $send['isPay'] = 'yes';
                    }
                    self::addOrderPayment($id);
        			$sql->add(db_basket, $send, $id);
                    break;
            }
		} else {
            switch($stop2) {
                case $stop2==$stop1:
                    $sql->add(db_basket, $send, $id);
                    break;
                case $stop2!=$stop1:
                    if($stop2==2) {
                        $send['isPay'] = 'yes';
                        self::addOrderPayment($id);
                    } else {
                        self::setStatusIsNoOk($id);
                    }
                    $sql->add(db_basket, $send, $id);
                    break;
            }
		}
		return $stop2;
    }

    static public function changePay($id) {
        if(self::addOrderPayment($id)) {
            cRegister::sql()->add(db_basket, array('isPay'=>'yes'), $id);
        }
    }

    static public function addOrderPayment($id, $type=null, $transaction=0) {
        $row = cRegister::sql()->placeholder("SELECT user, pay FROM ?t WHERE id=? AND isPay='no'", db_basket, $id)
                                    ->fetchAssoc();
        if($row) {
            $send = array('id'=>$id,
                          'user'=>$row['user'],
                          'data'=>date('Y-m-d H:i:s'),
                          'price'=>$row['pay'],
                          'type'=>$type,
                          'transaction'=>$transaction);
            if($transaction) {
                $is = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE id=? AND `type`=? AND transaction=?", db_basket_order, $id, $type, $transaction)
                                        ->numRows();
            } else {
                $is = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE id=? AND `user`=?", db_basket_order, $id, $row['user'])
                                        ->numRows();
            }
            if($is) {
                cRegister::sql()->placeholder("UPDATE ?t SET `isOk`='yes' WHERE id=?", db_basket_order, $id);
            } else {
                cRegister::sql()->replace(db_basket_order, $send);
            }
            cmfDiscount::update();
            return true;
        } else {
            self::setStatusIsOk($id);
        }
        return false;
    }

    static public function returnProductDump($list) {
        $_basket = cRegister::sql()->placeholder("SELECT p.id, p.product FROM ?t p WHERE p.id ?@", db_basket, (array)$list)
                                    ->fetchRowAll(0, 1);
        foreach($_basket as $product) {
            list(, $_basket) = cConvert::unserialize($product);
            $paramDump = cRegister::sql()->placeholder("SELECT p.id, p.count, p.paramDump FROM ?t p WHERE p.id ?@ AND p.isOrder='no'", db_product, array_keys($_basket))
                                    ->fetchAssocAll('id');
            foreach($_basket as $id=>$row) if(isset($paramDump[$id])) {
                $dump = cConvert::unserialize($paramDump[$id]['paramDump']);
                $count = $paramDump[$id]['count'];
                foreach($row['count'] as $pId=>$pValue) {
                    $cValue = array_sum($pValue);
                    if($pId) {
                        $dump[$pId] = get($dump, $pId)+$cValue;
                    }
                    $count += $cValue;
                }
                cRegister::sql()->add(db_product, array('count'=>$count, 'paramDump'=>cConvert::serialize($dump)), $id);
            }
        }
    }

    static public function setStatusIsOk($list) {
        cRegister::sql()->placeholder("UPDATE ?t SET `isOk`='yes' WHERE id ?@", db_basket_order, (array)$list);
        cmfDiscount::update();
    }

    static public function setStatusIsNoOk($list) {
        self::returnProductDump($list);
        cRegister::sql()->placeholder("UPDATE ?t SET `isOk`='no' WHERE id ?@", db_basket_order, (array)$list);
        cmfDiscount::update();
    }

    static public function delete($list) {
        self::returnProductDump($list);
        cRegister::sql()->placeholder("UPDATE ?t SET `delete`='yes' WHERE id ?@", db_basket, (array)$list);
        cRegister::sql()->placeholder("UPDATE ?t SET `delete`='yes' WHERE id ?@", db_basket_order, (array)$list);
        cmfDiscount::update();
    }

}

?>