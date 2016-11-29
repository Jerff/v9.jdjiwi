<?php


class model_catalog_brand extends cmfDriverModel {

    const name = '/brand/';
    const fields = 'visible|isUri';
    static public function update($id=null, $send=null) {
        if(self::isUpdate('visible', $send) and self::isUpdate('uri', $send)) {
            model_product::isProduct();
            self::updateUri(null, $id);
            model_product::updateSearch(null, $id);

        } elseif(self::isUpdate('visible', $send)) {
            model_product::isProduct();
            self::updateUri(null, $id);
            model_product::updateSearchId();

        } elseif(self::isUpdate('uri', $send)) {
            self::updateUri(null, $id);
            model_product::updateSearch(null, $id);
        }

        if(self::isUpdate('uri', $send)) {
            cmfContentUrl::updateShowcase();
        }
	}


    static public function delete($id) {
        cmfContentUrl::deleteWhere(array('/brand/', '/product/'), array('brand'=>$id));
        cmfSearchData::delete(self::name, array('brand'=>$id));
        model_product::isProduct();
        model_product::updateSearchId();
	}


    static public function updateUri($sec=null, $brand=null) {
        self::updateWhere($sec);
        self::updateWhere($brand);
        /* бренды */
		cRegister::sql()->placeholder("
		        REPLACE ?t SELECT '/brand/section/', sec2.id, b.id, 0, CONCAT_WS('/', sec2.isUri, b.uri) FROM ?t list LEFT JOIN ?t sec ON(sec.id=list.section) LEFT JOIN ?t sec2 ON(sec.id=sec2.id OR sec.path LIKE CONCAT('%[', sec2.id, ']%')) LEFT JOIN ?t b ON(list.brand=b.id) WHERE sec.isVisible='yes' AND b.visible='yes' AND ?w:sec AND ?w:b GROUP BY sec2.id, b.id",
				db_content_url, db_product_id, db_section, db_section, db_brand, $sec, $brand);
        cRegister::sql()->placeholder("
				DELETE FROM ?t WHERE page='/brand/section/' AND id NOT IN(SELECT id FROM ?t WHERE id IN(SELECT section FROM ?t WHERE isMenu='yes') AND isVisible='yes')",
				db_content_url, db_section, db_section_is_brand, $sec);

		cRegister::sql()->placeholder("
				REPLACE ?t SELECT '/brand/', 0, b.id, 0, CONCAT_WS('/', b.uri) FROM ?t b WHERE ?w:b",
				db_content_url, db_brand, $brand);
        cRegister::sql()->placeholder("
				DELETE FROM ?t WHERE page='/brand/' AND brand NOT IN(SELECT id FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE isMenu='yes') AND visible='yes')",
				db_content_url, db_brand, db_section_is_brand, $sec);
	    model_product::updateUri($sec, $brand);
	}

}

?>