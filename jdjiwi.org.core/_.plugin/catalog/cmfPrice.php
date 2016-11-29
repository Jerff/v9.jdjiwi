<?php

class cmfPrice {

	static public function format($p) {
		return number_format((int)$p, 0, '.', ' ');
	}
	static public function parse($p) {
		return (int)str_replace(array('.', ' '), '', $p);
	}

    static public function view(&$v) {
    	if($v['isDiscount']==1) {
            return self::format($v['price']*cmfCacheUser::getDiscount());
    	} else {
    		return self::format($v['price']);
    	}
    }

    static public function view2($price, $discount=1) {
    	if($discount==1) {
            return self::format($price*cmfCacheUser::getDiscount());
    	} else {
    		return self::format($price);
    	}
    }

    static public function price($price, $discount=1) {
    	if($discount==1) {
            return (int)($price*cmfCacheUser::getDiscount());
    	} else {
    		return $price;
    	}
    }

}

?>