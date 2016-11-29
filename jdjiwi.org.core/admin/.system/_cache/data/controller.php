<?php


class _cache_data_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->url()->setSubmit('/admin/cache/data/');

		$this->access()->writeAdd('clearCache');
	}


	public function runCommand($command=null) {
		cConfig::timeLimit();
		if(cAjax::isCommand('updateCache') or $command==='updateCache') {
			cmfCronCacheUpdate::start();
		}
		if(cAjax::isCommand('updateSearch') or $command==='updateSearch') {
			cmfCronUpdateSearch::init();
		}
	}


	public function updateCache() {
        $updateUrl = cJScript::quote(cUrl::admin()->command('/admin/cache/data/') ."&command=updateCache");
		$this->ajax()->script("cmf.ajax.command('{$updateUrl}', 'Обновлние кеша');");
	}

	public function updateSearch() {
        $updateUrl = cJScript::quote(cUrl::admin()->command('/admin/cache/data/') ."&command=updateSearch");
		$this->ajax()->script("cmf.ajax.command('{$updateUrl}', 'Обновление поискового индекса');");
	}

}

?>