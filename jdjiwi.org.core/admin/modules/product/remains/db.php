<?php


class product_remains_db extends product_list_db {

    protected function getFields() {
        return array('id', 'section', 'brand', 'articul', 'name', 'pos', 'price', 'count', 'param', 'paramDump');
	}


}

?>