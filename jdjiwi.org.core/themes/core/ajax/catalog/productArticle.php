<?php



//$r = cRegister::request();


$id = (int)cInput::post()->get('id');
$page = (int)cInput::post()->get('page');
if($page<1) exit;

$articleLimit = cSettings::get('site', 'productArticle');
$pageLimit = cSettings::get('site', 'limit');

$offset = ($page-1)*$articleLimit;
$sql = cRegister::sql();
$_article = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS a.id, a.header, a.notice, a.uri, p.uri AS pUri FROM ?t a LEFT JOIN ?t p ON(a.part=p.id) WHERE a.id IN(SELECT article FROM ?t WHERE tag IN(SELECT tag FROM ?t WHERE product=?)) AND p.visible='yes' AND a.visible='yes' ORDER BY a.date DESC LIMIT ?i, ?i", db_article, db_article_part, db_tag_article, db_tag_product, $id, $offset, $articleLimit)
				->fetchAssocAll();
foreach($_article as $k=>$v) {
	$_article[$k] = array(	'name'=> $v['header'],
							'notice'=> $v['notice'],
							'url'=> cUrl::get('/article/', $v['pUri'], $v['uri']));
}

$_articleUrl = array();
foreach(cmfMainPagination($page, $sql->getFoundRows(), $articleLimit, $pageLimit) as $key=>$value) {
	$_articleUrl[$value]['url'] = $key;
	if($key==$page) $_articleUrl[$value]['sel'] = 1;
}

ob_start();
?>
<div class="post"> <br />
<? foreach($_article as $v) { ?>
<div class="zag"><?=$v['name'] ?></div>
<p><?=$v['notice'] ?>&nbsp;<a href="<?=$v['url'] ?>">Подробнее</a></p>
<? } ?>

<? if(isset($_articleUrl)) { ?>
	<p class="pages"><b>страницы:</b>  <? cmfView::paginationAjax('cmfProductArticleList', $_articleUrl, "{id:{$id}}") ?></p>
<? } ?>
</div>
<?
cAjax::get()->html('#articleList', ob_get_clean());

?>