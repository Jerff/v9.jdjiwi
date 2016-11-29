<?php

class cCrumbsView {

    static public function view($url, $menu) {
        if (!$menu) {
            return;
        }
        ?><div class="location"><a href="<?= $url ?>">Главная</a> / <?
        foreach ($menu as $name => $url) {
            if (empty($url)) {
                ?><a><?= $name ?></a> / <?
            } else {
                ?><a href="<?= $url ?>"><?= $name ?></a> / <?
            }
        }
        ?></div><?
    }

}
?>