<?php


class cmfContact {


    static public function country() {
		return cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE visible='yes' ORDER BY pos, name", db_delivery_country)
									->fetchRowAll(0, 1);
	}

    static public function region($countryId) {
		if($_region = cmfCache::getParam('cmfContact::region', $countryId)) {
		} else {

			$_region = cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE country=? AND visible='yes' ORDER BY pos, name", db_delivery_region, $countryId)
									->fetchRowAll(0, 1);
			cmfCache::setParam('cmfContact::region', $countryId, $_region, 'region,country');
		}
		return $_region;
	}

}

?>