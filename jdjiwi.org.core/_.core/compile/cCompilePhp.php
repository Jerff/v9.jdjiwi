<?php

class cCompilePhp {
    /*
     *  compile sourse path
     */

    public function path($type, $file) {
        if (isComplile < 2) {
            return $file;
        }
        $hash = cHashing::hash($file);
        $compile = cCompilePath . $type . '/' . substr($hash, 0, 1) . '/' . substr($hash, 1, 2) . '/' . $hash . '.php';
        if (file_exists($compile)) {
            if (isComplile == 3)
                return $compile;
            else {
                if (filemtime($file) < filemtime($compile))
                    return $compile;
            }
        }
        file_put_contents($compile, $this->compile(cAppPathController . $p58 . '.php', true));
        return $compile;
    }

    /*
     * update
     */

    public function update() {
        foreach (cCompile::config()->fileList() as $name) {
            if (is_file($name)) {
                file_put_contents(
                        cCompilePath . $name, cmfCompile::php()->compile($name)
                );
            }
        }
        cDir::clear(cCompilePath);
    }

    private $file = null;
    private $class = null;

    public function compile($files, $class = false) {
        $this->file = array();
        $this->class = $class;
        $content = '';
        foreach ((array) $files as $file)
            $content .= $this->file($file);
        $content = preg_replace('#\?>\s?<\?php#', ' ', $content);

        $content = str_replace("\r", '', $content);
        return $content;
    }

    public function compile_only($content) {
        return preg_replace('#\?>\s?<\?php#', ' ', $content);
    }

    private function file($file) {
        if (isset($this->file[$file]))
            return '';
        $this->file[$file] = true;

        foreach (cCompile::config()->includePath() as $path) {
            if (is_file($path . $file)) {
                $content = cString::convertEncoding(php_strip_whitespace($path . $file));
                break;
            }
        }

        static $old = false;
        if (!isset($content)) {
            pre("не найден файл: $file in ({$old})");
            return '';
        }
        $old = $file;


//        $content = preg_replace_callback("#(\".*?\")|('.*?')|('.*?')|(//-->)|(//.*?\n)#S", array(&$this, 'comment1'), $content);
//        $content = preg_replace_callback("#(\".*?\")|('.*?')|(/\*.*?\*/)#sS", array(&$this, 'comment2'), $content);
//        //$content = preg_replace_callback("#(\".*?\")|('.*?')|(\s{3,})#sS",			array(&$this, 'comment3'), $content);
//        //$content = preg_replace_callback("#(\".*?\")|('.*?')|((\n|\{)\t)#sS",		array(&$this, 'comment4'), $content);


        $content = preg_replace_callback("#(\".*?\")|('.*?')|(\{.*?\})|((require|include)\('(.*?)'\);)#sS", array(&$this, 'includeFile'), $content);
        $content = preg_replace_callback("#(\".*?\")|('.*?')|(\{.*?\})|(cmf(LoadResponse|LoadAjax|LoadForm|LoadRequest)\(\);)#sS", array(&$this, 'loadLibrary'), $content);
        $content = preg_replace_callback("#(\".*?\")|('.*?')|(\{.*?\})|(cmf(Load|LoadConfig|LoadMain|LoadLibrary)\('(.*?)'\);)#sS", array(&$this, 'loadClass'), $content);
        $content = preg_replace_callback("#(\".*?\")|('.*?')|(\{.*?\})|(cmf(Model)\('(.*?)'\);)#sS", array(&$this, 'loadAdmin'), $content);

        $content = "<?php\n#include $file\n?>" . $content . "<?php\n#end $file\n?>";

        return $content;
    }

    private function includeFile($m) {
        if (!isset($m[6]))
            return $m[0];
        return ' ?>' . $this->file($m[6]) . '<?php ';
    }

    private function loadClass($m) {
        if (!isset($m[6]))
            return $m[0];

        if ($this->class) {
            $n = preg_replace('#(.*?)\/([^/]+).php#', '$2', $m[6]);
            if (class_exists($n))
                return $m[0];
        }
        $n = preg_replace('#(.*?)\/([^/]+)#', '$2', $m[6]);
        $file = $this->file($m[6] . '.php');

        return ' ?>' . $file . '<?php ';
    }

    private function loadAdmin($m) {
        if (!isset($m[6]))
            return $m[0];

        if ($this->class) {
            $n = preg_replace('#(.*?)\/([^/]+).php#', '$2', $m[6]);
            if (class_exists($n))
                return $m[0];
        }
        $n = preg_replace('#(.*?)\/([^/]+)#', '$2', $m[6]);

        switch ($m[5]) {
            case 'Model':
                $name = $m[5];
                $name = $name{0} === '_' ? '_' . str_replace('_', '/', substr($name, 1)) :
                        '_/' . str_replace('_', '/', $name);
                break;

            default:
                return $m[0];
        }
        $file = $this->file('_mvcModel/' . $name . '.php');
        return ' ?>' . $file . '<?php ';
    }

    private function loadLibrary($m) {
        if (!isset($m[5]))
            return $m[0];

        switch ($m[5]) {
            case 'LoadResponse':
                return "cmfLoad('ajax/cAjaxResponse');";

            case 'LoadAjax':
                return "cmfLoad('ajax/cAjax');";

            case 'LoadForm':
                return "cmfLoad('form/cmfForm');";

            case 'LoadRequest':
                return "cmfLoad('request/cmfRequest');";
        }
        return $m[0];
    }

    private function comment1($m) {
        if (isset($m[5]))
            return "\n";
        return $m[0];
    }

    private function comment2($m) {
        if (isset($m[3]))
            return '';
        return $m[0];
    }

    private function comment3($m) {
        if (isset($m[3]))
            return $m[3][0] . $m[3][0];
        return $m[0];
    }

    private function comment4($m) {
        if (isset($m[3]))
            return $m[4];
        return $m[0];
    }

}

?>
