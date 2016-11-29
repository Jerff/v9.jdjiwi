<?php


class cmfUpdateCacheLogDrive {

    private $log = array();

    public function free() {
        foreach($this->log as $type=>$list) {
            foreach($list as $name=>$isSub) {
                cRegister::sql()->replace(db_cache_update, array('type'=>$type, 'name'=>$name, 'isSub'=>(int)$isSub, 'date'=>date('Y-m-d H:i:s')));
            }
        }
    }

    static public function update($command, $id=null) {
        static $update = null;
        if(!$update) {
            $update = new cmfUpdateCache();
            register_shutdown_function(array(&$update, 'free'));
        }
        if(method_exists($update, $command)) {
			call_user_func_array(array(&$update, $command), array($id));
		}
    }

    // логирование команд
    private function save($type, $name='', $isSub=true) {
        $this->log[$type][$name] = $isSub;
    }


    protected function explode($command) {
		return explode(', ', $command);
	}

    //очистка кеша
    protected function clear() {
        $this->save('clear');
    }

    //удаление по тегам
    protected function deleteTags($tags) {
        foreach($this->explode($tags) as $tag) {
            $this->save('tag', $tag);
        }
    }

    //удаление страниц
    protected function deletePage($pages) {
        foreach($this->explode($pages) as $page) {
            $this->save('page', $page);
        }
    }

    //удаление по адресу
    protected function deleteUrl($url, $isSub=true) {
        $this->save('url', str_replace(cAppUrl, '', $url), $isSub);
    }

}

?>
