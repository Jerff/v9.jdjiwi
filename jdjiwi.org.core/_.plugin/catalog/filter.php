<?php


function cmfSectionFilter($sectionId, $brandId, $paramMainId, $paramMainValue, $param) {
	if(false===($_brand = cmfCache::getParam('cmfSectionFilter'. $sectionId, array($brandId, $paramMainId, $paramMainValue, $param)))) {

	    $sql = cRegister::sql();
	    $section = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND isMenu='yes' AND isVisible='yes'", db_section, $sectionId)
							->fetchAssoc();
	    $_sectionId = array($sectionId=>$sectionId);

		$_brand = $sql->placeholder("SELECT id, name FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE section ?@ AND visible='yes' GROUP BY brand) AND visible='yes' ORDER BY `pos`", db_brand, db_product, $_sectionId)
						->fetchRowAll(0, 1);

		$paramSearch = array();

		$search = array();
		foreach($param as $k=>$v) {
	        $i = 0;
	        if($paramMainId and $paramMainValue) {
	        	$search[$k] = $sql->getQuery("SELECT id FROM ?t WHERE param=? AND value=?", db_param_select, $paramMainId, $paramMainValue);
				$i++;
	        }
	        foreach($param as $k1=>$v2) if($k1!=$k and $v2) {
		        $querty = $sql->getQuery("SELECT id FROM ?t WHERE param=? AND value=?", db_param_select, $k1, $v2);
		        if(!$i) {
		        	$search[$k] = $querty;
		        } else {
                    if($i==1) {
                        $search[$k] = $sql->getQuery("SELECT id FROM ?t WHERE id IN({$search[$k]}) AND id IN({$querty})", db_product_id);
                    } else {
                        $search[$k] .= " AND id IN($querty)";
                    }
		        }
		        $i++;
	        }
		}


		$res = $sql->placeholder("SELECT id, name, value FROM ?t WHERE types=? AND `select`='yes' AND visible='yes' ORDER BY pos", db_param, $section['type'])
							->fetchAssocAll();
		$i = 0;
		$query = $sep = '';
		foreach($res as $row) {
			$id = $row['id'];
			$paramSearch[$id]['name'] = cString::strlen($row['name'])>20 ? preg_replace('~ ~', '<br />', $row['name'], 1) : $row['name'];
			$paramSearch[$id]['name'] = $row['name'];
			if($row['value']) {
				$paramSearch[$id]['param'] = unserialize($row['value']);
				$product = isset($search[$id]) ? "id IN({$search[$id]}) AND " : '';
				$query .= $sep . $sql->getQuery("(SELECT param, value FROM ?t WHERE $product param=? AND value ?@ AND id IN(SELECT id FROM ?t WHERE section ?@ AND brand ?@ AND visible='yes' AND price>0) GROUP BY value)", db_param_select, $id, array_keys($paramSearch[$id]['param']), db_product, $_sectionId, $brandId ? $brandId : array_keys($_brand));
				$sep = " \nUNION ";
	        }
		}

		$res = $sql->placeholder($query)->fetchRowAll(0, 1, 1);
		//pre($res);
		//$res = $sql->placeholder($query)->fetchRowAll( );
		//pre($res);
		foreach($paramSearch as $k=>$v) {
			foreach($v['param'] as $k2=>$v2) {
				if(!isset($res[$k][$k2])) {
					unset($paramSearch[$k]['param'][$k2]);
				}
			}
		}

		cmfCache::setParam('cmfSectionFilter'. $sectionId, array($brandId, $paramMainId, $paramMainValue, $param), array($_brand, $paramSearch), array('config', 'paramList', 'section'. $sectionId));
	} else {
	    list($_brand, $paramSearch) = $_brand;
	}
	return array($_brand, $paramSearch);
}

?>
