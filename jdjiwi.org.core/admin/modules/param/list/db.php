<?php


class param_list_db extends driver_db_list {

	public function returnParent() {
		return 'param_edit_db';
	}

	protected function getTable() {
		return db_param;
	}

	protected function getSort() {
		return array('name');
	}

	public function getParamList($section, $where) {
        $basket = null;
        $parent = $section;
        while(!$basket) {
            list($parent, $basket) = $this->sql()->placeholder('SELECT parent, `basket` FROM ?t WHERE id=?', db_section, $parent)
                                        ->fetchRow();
            if(!$parent or $basket) break;
        }

        $path = cLoader::initModul('catalog_section_edit_db')->getFeildRecord('path', $section);
        $path = cConvert::pathToArray($path);
        $path[$section] = $section;
        if($where) {
        	$where[] = 'AND';
        }
        if($basket) {
            $where[] = '(';
        }
        $where[] = $this->sql()->getQuery("p.id IN(SELECT param FROM ?t WHERE `group` ?@ AND visible='yes' UNION SELECT param FROM ?t WHERE `group` ?@ AND visible='yes')", db_param_group_notice, $path, db_param_group_select, $path);
        if($basket) {
            $where[] = $this->sql()->getQuery(" OR p.id=?)", $basket);
        }
        $fileds = array('id', 'name', 'type', 'value', 'prefix');
        $param = $this->sql()->placeholder("SELECT ?fields:p FROM ?t p LEFT JOIN ?t n ON(p.id=n.param) WHERE ?w:p ORDER BY n.pos, ?o:p", $fileds, $this->getTable(), db_param_group_notice, $where, $this->getSort())
        						->fetchAssocAll('id');
        foreach($param as $k=>$v) {
        	if($basket!=$k and $v['type']==='basket') {
                $param[$k]['type'] = 'checkbox';
        	}
        	if($v['prefix']) {
        		$param[$k]['name'] .= " ({$v['prefix']})";
        	}
        	$param[$k]['value'] = empty($v['value']) ? array() : unserialize($v['value']);
        }
        return $param;
	}

}

?>