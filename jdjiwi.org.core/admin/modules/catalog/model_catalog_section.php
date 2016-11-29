<?php


class model_catalog_section extends cmfDriverModel {

    const name = '/section/';
    static public function update($id=null, $send=null) {
        if(self::isUpdate('visible|isVisible', $send) and self::isUpdate('parent|uri|isUri', $send)) {
            model_product::isProduct();
            self::updateUri($id);
            model_product::updateSearch($id);

        } elseif(self::isUpdate('visible|isVisible', $send)) {
            model_product::isProduct();
            model_product::updateSearchId();

        } elseif(self::isUpdate('parent|uri|isUri', $send)) {
            self::updateUri($id);
            model_product::updateSearch($id);
        }

        if(self::isUpdate('parent|uri|isUri', $send)) {
            cmfContentUrl::updateShowcase();
        }
	}


    static public function delete($id) {
        cmfContentUrl::deleteWhere(array('/section/', '/brand/', '/product/'), array('id'=>$id));
        cmfSearchData::delete(self::name, array('section'=>$id));
	}


    static public function updateUri($sec=null) {
        self::updateWhere($sec);

        /* разделы */
		cRegister::sql()->placeholder("
				REPLACE ?t SELECT ?, sec.id, 0, 0, CONCAT_WS('/', sec.isUri) FROM ?t sec WHERE ?w:sec AND isVisible='yes'",
				db_content_url, self::name, db_section, $sec);
 		cRegister::sql()->placeholder("
				DELETE FROM ?t WHERE page=? AND id NOT IN(SELECT id FROM ?t WHERE id IN(SELECT section FROM ?t WHERE isMenu='yes') AND isVisible='yes')",
				db_content_url, self::name, db_section, db_section_is_brand);
        model_catalog_brand::updateUri($sec);
	}

}

?>