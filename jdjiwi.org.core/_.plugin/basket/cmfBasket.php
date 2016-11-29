<?php


cLoader::ajax();
cLoader::library('catalog/function');
cLoader::library('order/cmfOrder');
cLoader::library('order/cmfDiscount');
class cmfBasket {

	const name = 'basket';

	private $product = array();
	private $step = array();
	private $count = 0;
	private $price = 0;

	function __construct($isLoad = true) {
		if(!$isLoad) return;

		$product = cCookie::get(self::name .'Product');
		if(!$product) return;
		$this->filterData($product);
		$this->step = cSession::get(self::name .'Step');

		$this->count = cCookie::get(self::name .'Count');
		$this->price = cCookie::get(self::name .'Price');
	}


	private function filterData($data) {
		foreach(cConvert::unserialize($data) as $productId=>$list) {
            foreach((array)$list as $paramId=>$list2) {
                foreach((array)$list2 as $colorId=>$count) {
                    $this->product[(int)$productId][(int)$paramId][(int)$colorId] = (int)$count;
                }
            }
		}
	}


    public function isStep($step) {
        $count = 0;
        for($i=1; $i<=$step; $i++) {
            if(isset($this->step[$i])) $count++;
        }
        return $step==$count;
    }
    public function getStep($step) {
        return get($this->step, $step);
    }
    public function setStep($step, $value=true) {
        return $this->step[$step] = $value;
    }
    public function delStep($step) {
        unset($this->step[$step]);
    }


	public function getCount() {
		return $this->count;
	}
	public function getSum() {
		return number_format($this->price, 0, '', ' ');
	}


	public function loadUser($basket) {
		$this->filterData($basket);
		$this->initPrice();
		$this->setCokies();
	}
	protected function saveUser() {
		cRegister::getUser()->updateBasket($this->product ? serialize($this->product) : '');
	}


	public function save($isSaveUser=true) {
		$this->setCokies();
		if(cAjax::is()) {
			cAjax::get()->script('cmf.basket.header();');
		}
		if($isSaveUser) {
    		$this->saveUser();
        }
	}

	public function setCokies() {
		cCookie::set(self::name .'Product', serialize($this->product), 12);
		cSession::set(self::name .'Step', $this->step);
		cCookie::set(self::name .'Count', $this->count, 12);
		cCookie::set(self::name .'Price', cmfPrice::format($this->price), 12);
	}
	public function disable() {
		cCookie::del(self::name .'Product');
		cSession::del(self::name .'Step');
		cCookie::del(self::name .'Count');
		cCookie::del(self::name .'Price');
		$this->product = $this->step = null;
		$this->saveUser();
	}


	public function addProduct($productId, $paramId, $colorId, $count=1) {
		$this->product[$productId]
		    [$paramId]
		        [$colorId] =
		            get3($this->product, $productId, $paramId, $colorId) + $count;
	}

	public function setProduct($productId, $paramId, $colorId, $count) {
		if(!$productId or $count<1) return false;
		$this->product[$productId][$paramId][$colorId] = $count;
		return true;
	}

	public function delProduct($productId, $paramId, $colorId) {
		unset($this->product[$productId][$paramId][$colorId]);
		if(empty($this->product[$productId][$paramId])) {
		    unset($this->product[$productId][$paramId]);
		    if(empty($this->product[$productId])) {
    		    unset($this->product[$productId]);
    		}
		}
	}

	public function getProduct() {
		return $this->product;
	}
	public function isOrder() {
		return (bool)$this->product;
	}


	public function initPrice() {
		return $this->_initPrice($this->product);
	}

	private function _initPrice($_product) {
		$res = cmfOrder::initPrice($_product);
		list(, , $countAll, $priceAll) = $res;
		$this->count = $countAll;
		$this->price = $priceAll;
		return $res;
	}

	public function getBasketProduct($_basket=null, $pricePay=null, $isDelivery=null, $delivery=null) {
		if(is_null($isDelivery) and $res=$this->getStep('delivery')) {
            $isDelivery = $res['isDelivery'];
            $delivery = $res['deliveryPrice'];
		}
		list($header, $_basket, $countAll, $priceAll, $priceDiscount, $discount, $pricePay) = cmfOrder::initPrice($this->product, $_basket, $pricePay);
		if($isDelivery) {
            $priceDelivery = $priceDiscount+$delivery;
            $pricePay += $delivery;
		} else {
		    $priceDelivery = $priceDiscount;
		}

		$this->count = $countAll;
		$this->price = $priceAll;

		$priceAll = cmfPrice::format($priceAll);
		$priceDiscount = cmfPrice::format($priceDiscount);
		$priceDelivery = cmfPrice::format($priceDelivery);
		$discount = cmfPrice::format($discount);
		return array($header, $_basket, $countAll, $priceAll, $priceDiscount, $priceDelivery, $discount, $isDelivery, $delivery, $pricePay);
	}

	static public function updateUserOrder($userId=null) {
		$sql = cRegister::sql();
		if($userId) {
			$res = $sql->placeholder("SELECT id FROM ?t WHERE id ?@ AND register='yes' AND visible='yes'", db_user, (array)$userId)
						->fetchAssocAll();
		} else {
			$res = $sql->placeholder("SELECT id FROM ?t WHERE register='yes' AND visible='yes'", db_user)
						->fetchAssocAll();
		}
		$status = $sql->placeholder("SELECT id FROM ?t WHERE stop IN(2)", db_basket_status)
						->fetchRowAll(0);
		foreach($res as $row) {
			$sql->placeholder("UPDATE ?t SET userPay=(SELECT SUM(price) FROM ?t WHERE id IN(SELECT id FROM ?t WHERE status ?@ AND user=?) AND user=? AND isOk='yes')
			                             WHERE id=?", db_user, db_basket_order, db_basket, $status, $row['id'], $row['id']);
		}
	}

}

?>