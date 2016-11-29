<?php


class dump_size_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'dump_size_modul');

		// url
		$this->url()->setSubmit('/admin/dump/');

		$this->access()->writeAdd('newLine|updateDump');
		$this->access()->readAdd('onchangeParam');
	}


	protected function updateDump() {
        cmfCronRun::runModul('product.dump');
        $this->command()->reloadView();
        $this->ajax()->alert('Обновление завершено');
	}


    public function getLog($all=true) {
        $res = $this->sql()->placeholder("SELECT date, content FROM ?t WHERE important='yes' ORDER BY date DESC LIMIT 0, 20", db_product_dump_log)
                              ->fetchAssocAll();
        foreach($res as &$row) {
            if(strtotime($row['date']) + 10*24*60*60 - time()>0) {
                $row['isNew'] = true;
            }
            $row['date'] = date('d.m.Y H:i', strtotime($row['date']));
        }
        return $res;
	}

	protected function onchangeParam($id) {
		$this->modul()->onchangeParam($id);
	}

	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>