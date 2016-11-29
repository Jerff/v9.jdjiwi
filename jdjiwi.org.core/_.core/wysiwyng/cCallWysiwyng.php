<?

cLoader::library('wysiwyng/cWysiwyng');

class cCallWysiwyng {

    static public function start() {
        $path = cInput::get()->get('path');
        $number = cInput::get()->get('number');
        if (!$path) {
            $type = explode('-', cInput::get()->get('type'));
            if (count($type) == 5) {
                $path = $type[2];
                $number = $type[4];
                $type = $type[0];
                if ($type == 'All')
                    $type = null;
                cInput::get()->set('type', $type[0]);
                cInput::get()->set('path', $path);
                cInput::get()->set('number', $number);
            }
        }
        //var_dump($path, $number);
        $path = cWysiwyng::path($path, $number);
        //var_dump($path);
        cConfig::restoreHandler();
        return $path;
    }

}

?>