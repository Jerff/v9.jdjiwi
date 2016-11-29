<?php

cLoader::library('packer.php/class.JavaScriptPacker');

class cCompileJsCss {
    /*
     * config
     */

    public function pathWWW($type, $file) {
        return '/' . cCompile::config()->pathJsCss() . '/v/' . time() . '/' . $type . '/' . cHashing::hash($file);
    }

    public function pathCompile($type, $file) {
        return cWWWPath . cCompile::config()->pathJsCss() . '/' . cHashing::hash($file) . '.' . $type;
    }

    /*
     * update
     */

    public function update() {
        cDir::clear(cWWWPath . cCompile::config()->pathJsCss());
    }

    /*
     * данные
     */

    static private $value = null;

    static private function set($value) {
        self::$value = $value;
    }

    static public function get() {
        return self::$value;
    }

    /*
     * compile
     */

    public function compile($sFile) {
        switch ($type = preg_replace('~^.+\.(css|js|map)$~', '$1', $sFile)) {
            case 'js':
                header('Content-Type: application/x-javascript');
                $sep = ';';
                break;

            case 'css':
                header('Content-Type: text/css');
                $sep = '';
                break;
            
            default:
                exit;
        }

        $mList = explode(';', $sFile);
        $path = '';
        foreach ($mList as $key => $value) {
            if (preg_match('~\/~', $value)) {
                $path = dirname($value);
            } else {
                if ($path) {
                    $mList[$key] = $path . '/' . $value;
                }
            }
        }

        $content = '';
        foreach ($mList as $file) {
            if (cFile::is(cWWWPath . $file)) {
                $sourse = cString::convertEncoding(file_get_contents(cWWWPath . $file));
                self::set(dirname($file) . '/');
                switch ($type) {
                    case 'js':
                        $sourse = preg_replace_callback("#(@\s+sourceMappingURL=)([^\s]+.map)#sS", function($m) {
                            if (isset($m[2])) {
                                return $m[1] . cCompileJsCss::get() . $m[2];
                            }
                            return $m[0];
                        }, $sourse);
                        if (cString::strrpos($file, 'min') === false and cString::strrpos($file, 'pack') === false) {
                            $sourse = new JavaScriptPacker($sourse, 'None', false, false);
                            $sourse = $sourse->pack();
                        }
                        break;

                    case 'css':
                        $sourse = preg_replace_callback("#(url\s*\([\s\"\']*)([^\)\"\'\s]+)([\s\"\']*\))#sS", function($m) {
                            if (substr($m[2], 0, 3) === '../') {
                                return $m[1] . cCompileJsCss::get() . substr($m[2], 3) . $m[3];
                            } else if (substr($m[2], 0, 1) !== '/') {
                                return $m[1] . cCompileJsCss::get() . $m[2] . $m[3];
                            }
                            return $m[0];
                        }, $sourse);
                        break;
                }
                $content .= PHP_EOL . '/* ' . $file . ' */' . PHP_EOL . $sourse . PHP_EOL . $sep . PHP_EOL;
            }
        }
        cDebug::disable();
        echo $content;
        file_put_contents($this->pathCompile($type, $sFile), $content);
    }

    /*
     * compile header list
     */

    public function initHeader(&$mData) {
        if (!cCompile::is())
            return;
        $lastKey = $lastPath = array();
        foreach ($mData as $key => list($type, $value)) {
            switch ($type) {
                case 'string':
                    $lastKey = $lastPath = array();
                    break;

                case 'js':
                case 'css':
                    $path = dirname($value);
                    if (get($lastPath, $type) === $path) {
                        $mData[$lastKey[$type]][1] .= ';' . basename($value);
                        unset($mData[$key]);
                    } else {
                        if (isset($lastKey[$type])) {
                            $mData[$lastKey[$type]][1] .= ';' . $value;
                            unset($mData[$key]);
                        } else {
                            $lastKey[$type] = $key;
//                                $mData[$lastKey[$type]][1] = cCompile::file()->path($type) . $mData[$lastKey[$type]][1];
                        }
                        $lastPath[$type] = $path;
                    }
                    break;

                case 'jsSousre':
                    unset($lastKey['js'], $lastPath['js']);
                    break;

                case 'cssSousre':
                    unset($lastKey['css'], $lastPath['css']);
                    break;

                default:
                    break;
            }
        }
        foreach ($mData as $key => list($type, $value)) {
            switch ($type) {
                case 'js':
                case 'css':
                    $mData[$key][1] = $this->pathWWW($type, $value) . $value;
                    break;
                default:
                    break;
            }
        }
    }

}

?>
