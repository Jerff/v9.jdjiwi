<?php


class catalog_section_list_controller extends driver_controller_list_one {

	protected function init() {
		parent::init();
		$this->initModul('main',	'catalog_section_list_modul');

		// url
		$this->url()->setSubmit('/admin/catalog/section/edit/');
		$this->url()->setEdit('/admin/catalog/section/edit/');

		$this->url()->set('shop', '/admin/catalog/section/shop/');
		$this->url()->set('product', '/admin/product/');
		$this->url()->set('select', '/admin/param/group/select/');
		$this->url()->set('notice', '/admin/param/group/notice/');
	}

    public function viewListSiteUrl() {
        $arg = func_get_arg(0);
        return parent::viewListSiteUrl('/section/', $arg->isUri);
    }

	public function getNewUrl($opt=null) {
		$opt['id'] = null;
		$opt['select'] = $this->getFilter('parent');
		$opt['parent'] = null;
		$opt['isList'] = null;
		return $this->url()->getEdit($opt);
	}

	public function getProductUrl() {
		$opt = array('section'=>$this->getIndex());
		return $this->url()->get('product', $opt);
	}


	public function getShopUrl() {
		$opt = array('parentId'=>$this->getIndex());
		return $this->url()->get('shop', $opt);
	}

	public function getSelectUrl() {
		$opt = array('parentId'=>$this->getIndex());
		return $this->url()->get('select', $opt);
	}

	public function getNoticeUrl() {
		$opt = array('parentId'=>$this->getIndex());
		return $this->url()->get('notice', $opt);
	}


	public function delete($id) {
		$id = cLoader::initModul('catalog_section_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function getChild() {
		$listId = $this->getDataId();
		return $this->sql()->placeholder('SELECT parent, count(id) FROM ?t WHERE parent ?@ GROUP BY parent', db_section, $listId)
								->fetchRowAll(0, 1);
	}

	public function getProduct() {
		$count = array();
		foreach($this->getDataId() as $id) {
            $count[$id] = get($count, $id) + $this->sql()->placeholder("SELECT count(id) FROM ?t WHERE section IN(SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%')", db_product, db_section, $id, $id)
															->fetchRow(0);
		}
		return $count;
	}

}

?>