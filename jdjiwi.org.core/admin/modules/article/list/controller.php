<?php


class article_list_controller extends driver_controller_list {

	protected function init() {
		parent::init();
		$this->initModul('main',	'article_list_modul');

		// url
		$this->url()->setSubmit('/admin/article/');
		$this->url()->setEdit('/admin/article/edit/');
		$this->url()->set('attach', '/admin/article/attach/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/article/', $arg->uri);
    }

	public function filterSection() {
		$filter = cLoader::initModul('catalog_section_list_tree')->getNameList(array('level'=>array(0, 1)), array('isUri'));
		cGlobal::set('$sectionId', array_keys($filter));
		$filter[-1]['name'] = 'Без разделов';
		$filter[0]['name'] = 'Все разделы';
		return parent::abstractFilter($filter, 'section', 'end');
	}

	public function delete($id) {
		$id = cLoader::initModul('article_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function getProductUrl($opt=null) {
		$opt['article'] = $this->id()->get();
		$opt['page'] = 1;
		return $this->url()->get('attach', $opt);
	}

	public function attachProduct() {
		return $this->sql()->placeholder("SELECT article, count(product2) AS count FROM ?t WHERE article ?@ GROUP BY article", db_article_attach, $this->getDataRecord())
                              ->fetchRowAll(0, 1);
	}


}

?>