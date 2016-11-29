<?php



cLoader::library('basket/cmfHistory');
$history = new cmfHistory();

ob_start();
foreach($history->getProduct() as $id=>$row) { ?>
<a href="<?=$row['url'] ?>"><?=cString::subContent($row['name'], 0, 40) ?></a>
<? } ?>
<script type="text/javascript">
$('.productHistory').show();
</script>
<?
cAjax::get()->html('#productHistoryList', ob_get_clean());

?>