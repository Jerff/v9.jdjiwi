<?php


class article_list_db extends driver_db_list {

	public function returnParent() {
		return 'article_edit_db';
	}

	protected function getTable() {
		return db_article;
	}

    protected function getWhereFilter() {
        $filter = array();
        $section = $this->getFilter('section');
        if($section>0) {
            $filter[] = $this->sql()->getQuery("section IN(SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%')", db_section, $section, $section);
        } elseif(!$section) {
            $filter['section'] = cGlobal::get('$sectionId');
        } else {
            $filter[] = $this->sql()->getQuery("`section` NOT ?@", cGlobal::get('$sectionId'));
        }
		return $filter;
	}

	protected function getSort() {
		return array('isMain'=>'ASC', 'date'=>'DESC');
	}

	protected function getFields() {
		return array('id', 'date', 'section', 'header', 'uri', 'notice', 'isMain', 'visible');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		$row['notice'] = cString::subContent($row['notice'], 0, 100);
		parent::loadData($row);
	}

	public function updateData($list, $send) {
        if(isset($send['isMain'])) {
			$this->command()->reloadView();
        }
	}


}

?>