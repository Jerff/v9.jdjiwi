<?php


class basket_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_basket;
	}

    public function filterPrice($price) {
        return str_replace(' ', '', $price);
    }

	public function formatPrice($price) {
		return number_format($price, 0, '.', ' ');
	}

	public function loadData(&$row) {
		$row['registerDate'] = date("d.m.Y H:i", strtotime($row['registerDate']));
		$row['changeDate'] = date("d.m.Y H:i", strtotime($row['changeDate']));
		parent::loadData($row);
	}

    public function saveEnd($id, $send) {
        parent::saveEnd($id, $send);
        if(!empty($send['deliveryCod'])) {
            cmfCronBasketDeliveryStatus::update($this->id()->get());
        }
    }

}

?>