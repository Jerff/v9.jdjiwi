<?php


class cmfDelivery {

	static public function get($price) {
        $d = 0;
        foreach(self::getList() as $k=>$v) {
            list($min, $max) = $v;
            if($min<=$price and $price<$max) {
            	$d = $k;
            }
        }
        return $d;
	}

	static private function &getList() {
		if(false===($data = cmfCache::get('cmfDelivery::getList'))) {

			$res = cRegister::sql()->placeholder("SELECT delivery, price FROM ?t ORDER BY price", db_basket_delivery)
												->fetchAssocAll();
			$data = array();
			$min = 0;
			foreach($res as $row) {
				$max = $row['price'];
				$data[$row['delivery']] = array($min, $max-1);
				$min = $max;
			}
			$data[0] = array($min, 9999999999999);

			cmfCache::set('cmfDelivery::getList', $data, 'delivery,order');
		}
		return $data;
	}
}

?>