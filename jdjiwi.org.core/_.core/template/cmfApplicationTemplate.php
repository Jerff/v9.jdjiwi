<?php

class cmfApplicationTemplate {

    private $teplate = null;
    private $mData = array();
    private $isCacheMain = true;

    public function __construct() {
        $this->setTeplate('main.index.php');
    }

    public function setTeplate($t) {
        $this->teplate = $t;
    }

    private function teplate() {
        return $this->teplate;
    }

    private function resetCacheMain() {
        $this->isCacheMain = true;
    }

    private function setCacheMain($c) {
        $this->isCacheMain &= $c;
    }

    private function getCacheMain() {
        return $this->isCacheMain;
    }

    public function main() {
        $this->resetCacheMain();

        $page = cPages::getMain();
        if (cmfCache::isNoPages())
            $cache = false;
        else
            $cache = !cPages::getPage($page)->noCache;

        if ($cache) {
            cmfCacheSite::readMainPage(cmfCacheSite::getFileMain($page));
            $c = $this->mainRun($page);
            if ($this->getCacheMain()) {
                cmfCacheSite::savePage(cmfCacheSite::getFileMain($page), $c);
            }
            return $c;
        } else {
            return $this->mainRun($page);
        }
    }

    private function mainRun($page) {
        $templateId = cPages::getPage($page)->template;
        $this->setTeplate(cPages::template()->get($templateId));
        $content = $this->run($page);

        if (is_int($templateId)) {
            ob_start();
            $content = include(cAppPathPage . $this->teplate());
            $content = ob_get_clean();
        }
        return $content;
    }

    private function &runAll() {
        $m = array();
        foreach (func_get_args() as $p)
            $m[] = $this->run($p);
        return $m;
    }

    private function run($page) {
        $conf = cPages::getPage($page);
        if (empty($conf)) {
            return false;
        }

        cPages::setItem($page);

        if (cmfCache::isNoPages())
            $cache = false;
        else
            $cache = !$conf->noCache;
        $this->setCacheMain($cache);


        if ($cache) {
            if (isset($conf->param1) and isset($conf->param2)) {
                $page .= cmfCacheSite::cmfCacheSite();
            } else
            if (isset($conf->param2))
                $page .= cmfCacheUser::getDiscount();
            else
                $page .= cmfCacheUser::getDiscount();

            if (isset($conf->noUrl)) {
                if (isset($conf->isMain)) {
                    $file = cmfCacheSite::getFilePageOfMain(cPages::getMain(), $page);
                } else {
                    $file = cmfCacheSite::getFilePage($page);
                }
            } else {
                $url = isset($conf['Request']) ? cInput::url()->uri() : cInput::url()->path();
                if (isset($conf['isMain'])) {
                    $file = cmfCacheSite::getFilePageUrlOfMain(cPages::getMain(), $page, $url);
                } else {
                    $file = cmfCacheSite::getFilePageUrl($page, $url);
                }
            }
            if (!$c = cmfCacheSite::readPage($file)) {
                $c = $this->runPage($conf->path);
                cmfCacheSite::savePage($file, $c);
            }
            return $c;
        }

        return $this->runPage($conf->path);
    }

    private function runPage($path) {
        ob_start();
        $r = $this->php($path);
        if ($r !== 1) {
            if ($r === 404) {
                header("HTTP/1.0 404 Not Found");
            }
            if ($r === 404 or $r === 403) {
                $r = '/' . $r . '/';
            }
            cPages::setMain($r);
            echo $this->main();
            exit;
        }
        $this->phtml($path);
        return ob_get_clean();
    }

    public function assing($name, &$value) {
        if ($name !== 'p58') {
            $this->mData[$name] = $value;
        }
    }

    public function assing2($name, $value) {
        if ($name !== 'p58') {
            $this->mData[$name] = $value;
        }
    }

    private function &getAssing() {
        $v = $this->mData;
        $this->mData = array();
        return $v;
    }

    private function php($p58) {
        return require(cCompile::path('application', cAppPathController . $p58 . '.php'));
    }

    private function phtml($p58) {
        extract($this->getAssing());
        require(preg_replace('~(/([^\/.]+)(\.php))$~isuU', '/templates/$2.phtml', cCompile::path('application', cAppPathController . $p58 . '.php')));
    }

}

?>
