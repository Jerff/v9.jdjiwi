<?php

cLoader::library('patterns/cPatternsStaticRegistry');
cLoader::library('wysiwyng/cWysiwyngConfig');
cLoader::library('wysiwyng/cWysiwyngKCKeditor');

class cWysiwyng extends cPatternsStaticRegistry {

    static private function config() {
        return self::register('cWysiwyngConfig');
    }

    static private function driver() {
        return self::register('cmfWysiwyngKCKeditor');
    }

    static public function path($path, $number) {
        if (!$path or !$number)
            exit;

        $mMap = self::config()->map();
//        var_dump($mMap);
        if (!isset($mMap[$path]))
            exit;
        $controller = $mMap[$path];
        if (is_array($controller)) {
            list($path, $controller) = $controller;
        } else {
            $path = path_wysiwyng . $path . '/';
        }

        $controller = cLoader::initModul($controller);
        cAccess::isWrite($controller);

        if (!$controller->wysiwyngIsRecord($number))
            exit;
        if (!$path)
            exit;
        $path = $path . $number . '/';
//        $filePath = cWWWPath . $path;
//        $path = '/' . $path;
        return array('/' . $path, cWWWPath . $path);
    }

    static public function create($path, $number) {
        /* if(!$path or !$number) return;
          $path = cWWWPath . $path;
          if(!cmfDir::isDir($path)) {
          if(!cmfDir::newDir($path)) return;
          }
          $path .= $number .'/';
          if(!cmfDir::isDir($path)) {
          if(!cmfDir::newDir($path)) return;
          } */
    }

    static public function clear($path, $number) {
        if ($path and $number) {
            cDir::clear(cWWWPath . $path . $number . '/', true);
        }
    }

    public function recordPath($modul) {
        $mMap = self::config()->map();
        while ((list($k, $v) = each($mMap))) {
            if (is_string($v)) {
                if ($modul === $v) {
                    return path_wysiwyng . $k . '/';
                }
            } else {
                if ($modul === $v[1]) {
                    return $v[0];
                }
            }
        }
        return false;
    }

}

?>