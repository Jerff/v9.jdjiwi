<?php


class cmfPaginationView {

    static public function view($page) { ?>
    <div class="next-page bcont">
        <a <? if($page['prev']) { ?>href="<?=$page['prev']['url'] ?>"<? } ?> class="prew">&lt; Предыдущая</a>
        <div class="pages"><div class="p2"><div class="p3">
            <ul>
            <? foreach($page['list'] as $k=>$v) { ?>
                <li><a href="<?=$v['url'] ?>" <? if(isset($v['is'])) { ?>class="active"<? } ?>><?=$v['name'] ?></a></li>
            <? } ?>
            </ul>
        </div></div></div>
        <a <? if($page['next']) { ?>href="<?=$page['next']['url'] ?>"<? } ?> class="prew next">Следующая ></a>
    </div>
<?  }


    static public function viewHistoty($page) { ?>
    <div class="next-page next-page-history bcont">
        <a <? if($page['prev']) { ?>href="<?=$page['prev']['url'] ?>"<? } ?> class="prew">&lt; Предыдущая</a>
        <div class="pages"><div class="p2"><div class="p3">
            <ul>
            <? foreach($page['list'] as $k=>$v) { ?>
                <li><a href="<?=$v['url'] ?>" <? if(isset($v['is'])) { ?>class="active"<? } ?>><?=$v['name'] ?></a></li>
            <? } ?>
            </ul>
        </div></div></div>
        <a <? if($page['next']) { ?>href="<?=$page['next']['url'] ?>"<? } ?> class="prew next">Следующая ></a>
    </div>
<?  }


}

?>
