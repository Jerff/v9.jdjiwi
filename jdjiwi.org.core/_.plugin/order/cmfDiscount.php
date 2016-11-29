<?php


class cmfDiscount {

    static public function update() {
        $sql = cRegister::sql();
        $sql->placeholder("UPDATE ?t u SET u.userPay=(SELECT IF(SUM(price), SUM(price), 0) FROM ?t b WHERE u.id=b.user AND `delete`='no' AND `isOk`='yes')", db_user_data, db_basket_order);

        $res = $sql->placeholder("SELECT price, discount FROM ?t ORDER BY price", db_discount)
				   ->fetchRowAll();

        $itemPrice = 0;
        $itemDiscount = 1;
        foreach($res as $v) {
            list($price, $discount) = $v;
            $sql->placeholder("UPDATE ?t SET discount=? WHERE userPay>=? AND userPay<?", db_user_data, $itemDiscount, $itemPrice, $price);
            $itemPrice = $price;
            $itemDiscount = $discount;
        }
        $sql->placeholder("UPDATE ?t SET discount=? WHERE userPay>=?", db_user_data, $itemDiscount, $itemPrice);
    }

    static public function searchPrice($price) {
        $res = cRegister::sql()->placeholder("SELECT price, discount FROM ?t ORDER BY price", db_discount)
				                    ->fetchRowAll(0, 1);
	    $itemDiscount = 1;
	    foreach($res as $k=>$v) {
            if($price<$k) {
                break;
            }
            $itemDiscount = $v;
	    }
        return $itemDiscount;
    }

}

?>