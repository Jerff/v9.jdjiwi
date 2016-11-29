<?php

class cmfProductUrl {


    static public function search($filter) {
        $str = $sep = '';
        $new = array();
        foreach(array('section', 'brand', 'name') as $k) {
            if(isset($filter[$k])) {
                $new[$k] = $filter[$k];
            }
        }
        foreach($new as $k=>$v) {
            $str .= $sep . $k .'/'. $v;
            $sep = '/';
    	}
    	return $str;
    }

	static public function replace() {
        $arg = func_get_args();
        $filter = array_shift($arg);
        switch(count($arg)) {
            case 6:
                $filter[$arg[4]] = $arg[5];
            case 4:
                $filter[$arg[2]] = $arg[3];
            case 2:
                $filter[$arg[0]] = $arg[1];
                break;
            case 5:
                $filter[$arg[3]] = $arg[4];
            case 3:
                $filter[$arg[0]][$arg[1]] = $arg[2];
                break;
        }
	    return self::generate($filter);
    }

	static public function generate($filter, $ignor = array()) {
    	$str = $sep = '';
    	$new = array();
    	foreach(array('section', 'name', 'param', 'type', 'sort', 'page', 'limit') as $k) {
    	    if(isset($filter[$k]) and !in_array($k, $ignor)) {
    	    	$new[$k] = $filter[$k];
    	    }
    	}
    	foreach($new as $k=>$v) if($v!==null) {
            switch($k) {
                case 'sort':
                    if($v!=='new') {
                    	$str .= $sep . $k .'/'. $v;
                    }
                    break;

                case 'param':
                    if(is_array($v)) {
                    	$sep = $str2 = '';
                    	for($i=0; $i<7; $i++) {
                            $str2 .= $sep . get($v, $i, 0);
                            $sep = '-';
                        }
                        $v = $str2==='0-0-0-0-0-0-0' ? '' : $str2;
                    }
                    if($v) {
                        $str .= '/param/'. $v;
                    }
                    break;

                case 'name':
                    if(!empty($v)) {
                        $str .= '/name/'. $v;
                    }
                    break;

                case 'section':
                    $str .= $v;
                    break;

                case 'type':
                    $str .= '/'. $v;
                    break;

                case 'page':
                    if($v!=1) {
                        $str .= '/page_'. $v;
                    }
                    break;

                default:
                    $str .= $sep . $k .'/'. $v;
                    break;
            }
            $sep = '/';
        }
    	return $str;
    }

}

?>