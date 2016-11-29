<?php


class subscribe_mail_list_db extends driver_db_list {

	public function returnParent() {
		return 'subscribe_mail_edit_db';
	}

	protected function getTable() {
		return db_subscribe_mail;
	}


	protected function getSort() {
		return array('email');
	}

    protected function getWhereFilter() {
        $filter = array();

		if($type = $this->getFilter('type')) {
            $filter['type'] = $type;
            $filter[] = 'AND';
        }

		if($email = $this->getFilter('email')) {
            $filter['email'] = urldecode($email);
			$filter[] = 'AND';
		}

		$filter[] = 1;
		return $filter;
	}

}

?>