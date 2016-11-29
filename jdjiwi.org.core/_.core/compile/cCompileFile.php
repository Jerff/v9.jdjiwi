<?php

cLoader::library('packer.php/class.JavaScriptPacker');

class cCompileFile {

    private $mInclude = null;

    private function reset() {
        $this->mInclude = array();
    }

    private function pack($list, $file, $js) {
        if (isset($this->mInclude[$file]))
            return '';
        foreach ($list as $dir) {
            if (is_file($dir . $file)) {
                $sourse = cString::convertEncoding(file_get_contents($dir . $file));
                break;
            }
        }
        if (!isset($sourse))
            return '';

        $include = '';
        preg_match_all('~//include\((.+)\);~', $sourse, $search);
        if ($search[1]) {
            foreach ($search[1] as $v)
                if (!isset($this->mInclude[$v])) {
                    $include .= $this->pack($list, $v, $js);
                }
        }
        $this->mInclude[$file] = 1;

        if (cString::strrpos($file, 'min') === false and cString::strrpos($file, 'pack') === false and $js) {
            $sourse = new JavaScriptPacker($sourse, 'None', false, false);
            $sourse = $sourse->pack();
        }
        return $include . "\n" . $sourse;
    }

    public function compile($name, $js = true) {
        $sep = $js ? ';' : '';
        $sourse = '';
        list($name, $list) = $name;
        $this->reset();
        foreach ($list as $dir) {
            $prefix = preg_replace('~^.+(\..+)$~i', '$1', $name);
            foreach (cDir::getFiles($dir) as $file) {
                if ($name != $file and cString::strrpos($file, $prefix) !== false) {
                    $sourse .= $this->pack($list, $file, $js);
                    $sourse .= "\n{$sep}\n";
                }
            }
        }

        if ($sourse) {
            file_put_contents(cCompile::config()->soursePath() . $name, $sourse);
        }
    }

}

?>
