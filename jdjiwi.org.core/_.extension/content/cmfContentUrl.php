<?php


class cmfContentUrl {

	static public function update() {
		model_content_info::updateUri();
		model_content::updateUri();

		self::updateShowcase();

		model_catalog_section::updateUri();
	}

	static public function updateShowcase() {
		model_baner::updateUri();
		model_showcase::updateUri();
		model_catalog_showcase::updateUri();
	}

 	public static function isUrlExists($page, $id, $uri, $brand=0, $product=0) {
        $url = '/'. $uri . '/';
        return cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE `part`='main' AND (`small_name`=? OR `url`=?)", db_pages_main, $url, $url)
									->numRows()
        		or cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE `url`=? AND `url` NOT IN(SELECT `url` FROM ?t WHERE `id`=? AND `page`=? AND `brand`=? AND `product`=?)", db_content_url, $uri, db_content_url, $id, $page, $brand, $product)
									    ->numRows();
	}

 	public static function deleteWhere($page, $where) {
        $where[] = 'AND';
        $where['page'] = $page;
        cRegister::sql()->del(db_content_url, $where);
	}

 	public static function delete($page, $list) {
        $where = array('id'=>(array)$list);
        $where[] = 'AND';
        $where['page'] = $page;
        cRegister::sql()->del(db_content_url, $where);
	}

}

?>