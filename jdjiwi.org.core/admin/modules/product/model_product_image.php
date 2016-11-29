<?php


class model_product_image extends cmfDriverModel {

    const name = '/product/image/';
    static public function update($id=null, $send=null) {
        if(self::isUpdate('color', $send)) {
            model_product::updateColor($id);
        }
        if(self::isUpdate('color', $send)) {
        }
        if(self::isUpdate('pos|visible|image|image_main|image_section|image_product|image_small', $send)) {
            self::updateImage($id);
        }

        self::updateCache($id);
	}

    static private function updateParentImage($id) {
        self::updateWhere($id);
        cRegister::sql()->placeholder("UPDATE ?t p SET p.image_section=(SELECT i.image_section FROM ?t i WHERE i.product=p.id AND visible='yes' ORDER BY i.pos DESC LIMIT 0, 1),
                                                      p.image_product=(SELECT i.image_product FROM ?t i WHERE i.product=p.id AND visible='yes' ORDER BY i.pos DESC LIMIT 0, 1),
                                                      p.image_small=(SELECT i.image_small FROM ?t i WHERE i.product=p.id AND visible='yes' ORDER BY i.pos DESC LIMIT 0, 1) WHERE ?w", db_product, db_product_image, db_product_image, db_product_image, $id);
    }

    static private function updateImage($id) {
        self::updateWhere($id);
        cRegister::sql()->placeholder("UPDATE ?t p SET p.image_section=(SELECT i.image_section FROM ?t i WHERE i.product=p.id AND visible='yes' ORDER BY i.pos DESC LIMIT 0, 1),
                                                      p.image_product=(SELECT i.image_product FROM ?t i WHERE i.product=p.id AND visible='yes' ORDER BY i.pos DESC LIMIT 0, 1),
                                                      p.image_small=(SELECT i.image_small FROM ?t i WHERE i.product=p.id AND visible='yes' ORDER BY i.pos DESC LIMIT 0, 1) WHERE id=(SELECT product FROM ?t WHERE ?w LIMIT 0, 1)", db_product, db_product_image, db_product_image, db_product_image, db_product_image, $id);
    }

    static public function updateCache($id) {
        cmfUpdateCache::update('product', $id);
	}


    static public function delete($id) {
	    model_product::updateColor($id);
	    self::updateParentImage($id);
	}

}

?>