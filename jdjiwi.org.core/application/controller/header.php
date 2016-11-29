<?php

include_once(cAppPathController . 'header.include.php');

if ($head = cmfCache::get('_header')) {
    list($head, $callback, $isCallBack, $content, $showcase, $_catalog) = $head;
} else {

    $head = cSettings::get('seo', 'head');
    $callback = cSettings::get('user', 'callback');
    $isCallBack = cSettings::get('showcase', 'callBackOn') === 'yes';

    $showcase = cSettings::get('showcase');
    $showcase['logo'] = cBaseImgUrl . path_config . $showcase['logo'];
    $showcase['phone'] = array_map('trim', explode(',', $showcase['phone']));
    foreach ($showcase['phone'] as $k => $v) {
        $showcase['phone'][$k] = explode(')', $v);
        $showcase['phone'][$k][0] .= ')';
    }

    $phone = cRegister::sql()->placeholder("SELECT phone FROM ?t WHERE id='contact/main' LIMIT 0, 1", db_main)
            ->fetchRow(0);
    $_catalog = cRegister::sql()->placeholder("SELECT id, name, isUri FROM ?t WHERE parent='0' AND id IN(SELECT section FROM ?t WHERE brand='0' AND isMenu='yes') AND isVisible='yes' ORDER BY pos", db_section, db_section_is_brand)
            ->fetchAssocAll('id');
    foreach ($_catalog as $id => $v) {
        $_catalog[$id] = array('name' => nl2br($v['name']),
            'url' => cUrl::get('/section/', $v['isUri']));
    }

    $url = cInput::url()->uri();
    $content = cRegister::sql()->placeholder("SELECT content FROM ?t WHERE `type`='header' AND adress=? AND visible='yes' LIMIT 0, 1", db_content_pages, $url)
            ->fetchRow(0);
    if (!$content) {
        $res = cRegister::sql()->placeholder("SELECT adress, content FROM ?t WHERE `type`='header' AND isReg='yes' AND visible='yes'", db_content_pages)
                ->fetchAssocAll();
        foreach ($res as $v) {
            $preg = str_replace('\{\*\}', '.*', preg_quote($v['adress']));
            if (preg_match('~' . $preg . '~is', $url)) {
                $content = $v['content'];
                break;
            }
        }
    }

    cmfCache::set('_header', array($head, $callback, $isCallBack, $content, $showcase, $_catalog), 'sectionList,contact');
}
cmfMenu::select('$rootId', $_catalog);

$this->assing('head', $head);
$this->assing('showcase', $showcase);

$this->assing('callback', $callback);
if ($content)
    $this->assing('content', $content);

if ($isCallBack) {
    $this->assing2('callBackUrl', cUrl::get('/call-back/'));
}
$this->assing2('index', cUrl::get('/index/'));
$this->assing2('basket', cUrl::get('/basket/'));
$this->assing('_catalog', $_catalog);


$this->assing2('userRegister', cUrl::get('/user/register/'));
$this->assing2('userEnter', cUrl::get('/user/enter/'));
$this->assing2('user', cUrl::get('/user/'));
$this->assing2('userExit', cUrl::get('/user/exit/'));


$this->assing2('isMain', cPages::isMain('/index/'));

$this->assing2('searchUrl', cGlobal::is('$searchUrl') ? cGlobal::get('$searchUrl') : cUrl::get('/search/'));
$this->assing2('defaultName', 'Поиск...');
$this->assing2('searchName', cGlobal::get('$searchName'));
?>
