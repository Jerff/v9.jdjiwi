<?php


class view_gallery extends view_list {

 	static public function posMove($jsModul, $name, $key, $pos) {
        $html = ' ';
 		if($key) {
			$cAppUrl = cAppUrl;
        	$html .=<<<HTML
		<table>
		<tr>
		<td rowspan="3" class="pos">
			<span id="{$name}posView{$key}">{$pos}</span>
		    <span id="{$name}posText{$key}" class="cHide">
		    <input id="{$name}pos{$key}" type="text" value="{$pos}" size="3"><input id="{$name}posOld{$key}" type="hidden" value="{$pos}">
		    </span></td>
		    <td><a href="javascript:void(0);" onclick="{$jsModul}.move1Ajax('{$key}');"><img src="{$cAppUrl}/sourseImage/admin/pos_up.gif" class="img"></a></td>
		</tr>
		<tr>
		    <td height="11px"><a href="javascript:void(0);" onclick="{$jsModul}.moveMoveAjax(this, '{$name}', '{$key}');"><img src="{$cAppUrl}/sourseImage/admin/pos_center.gif" class="img"></a></td>
		</tr>
		<tr>
		    <td height="9px"><a href="javascript:void(0);" onclick="{$jsModul}.move2Ajax('{$key}');"><img src="{$cAppUrl}/sourseImage/admin/pos_down.gif" class="img"></a></td>
		</tr>
		</table>
HTML;
 		}
 		return $html ;
 	}

	static public function startTR(&$modul) {
		static $name = null;
		static $id = 0;
		if($name!==get_class($modul)) {
            $name = get_class($modul);
            $id = 0;
		}
		?><div id="<?=$modul->getHtmlIdDel() ?>" class="galleryView"><?
	}

	const width = 400;
	static public function preview(&$modul, $jsModul, &$data) {
	    if(!$modul->getId() or !$data->image_small) return;
	    list($width, $height) = $modul->getGallerySize();
	    $image = $modul->getGalleryId();
	    $real = cSettings::read('gallery', 'real');
	    $view = self::width;
?>


<div class="container gallery">
  <div style="float: left; width: 50%;">

    <p class="instructions">
      Рамку можно перетаскивать и изменять размер
    </p>

    <div class="frame" style="margin: 0 0.3em; width: <?=$view ?>px;">
      <img id="galleryPhotoId" src="<?=cBaseImgUrl . $modul->getGalleryPath() . $data->image_main ?>" style="max-width: 100%"/>
    </div>
  </div>

  <div style="float: left; width: 50%;">
    <p style="font-size: 110%; font-weight: bold; padding-left: 0.1em;">
      &nbsp;&nbsp;&nbsp;&nbsp;Превью
    </p>

    <div class="frame" style="margin: 0 1em; width: <?=$width ?>px;">
      <div id="galleryPreviewId" style="width: <?=$width ?>px;">
        <img src="<?=cBaseImgUrl . $modul->getGalleryPath() . $data->$image .'?'. time() ?>" style="max-width: 100%"/>
      </div>
    </div>
    <br />
    <table style="margin-top: 1em;" class="cHide">
      <thead>
        <tr>
          <th colspan="2" style="font-size: 110%; font-weight: bold; text-align: left; padding-left: 0.1em;">
            Координаты
          </th>
          <th colspan="2" style="font-size: 110%; font-weight: bold; text-align: left; padding-left: 0.1em;">
            Размеры
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: 10%;"><b>X<sub>1</sub>:</b></td>
 		      <td style="width: 30%;"><input type="text" id="x1" name="x1" value="-" /></td>
 		      <td style="width: 20%;"><b>Ширина:</b></td>
   		    <td><input type="text" value="-" id="w" name="w"/></td>
        </tr>
        <tr>
          <td><b>Y<sub>1</sub>:</b></td>
          <td><input type="text" id="y1" name="y1" value="-" /></td>
          <td><b>Высота:</b></td>
          <td><input type="text" id="h" value="-" /></td>
        </tr>
        <tr>
          <td><b>X<sub>2</sub>:</b></td>
          <td><input type="text" id="x2" name="x2" value="-" /></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td><b>Y<sub>2</sub>:</b></td>
          <td><input type="text" id="y2" name="y2" value="-" /></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>


	<!--//<p>Нужно получить размер</p>
	<label>Ширина: </label>
		<input type="button" value="<?=$width ?>" size="5"/>
	<label>Высота: </label>
		<input type="button" value="<?=$height ?>" size="5"/>
	<br /><br />//-->
    <label><input type="checkbox" name="real" <?=$real ? 'checked="true"' : '' ?>> - если поставить эту галочку, то будет сохранен оригинал выделения,
    если сброшена, то выделеная область будет подгонятся под размеры.</label>
    <br /><br />
    <?=cmfAdminView::onclickType1($jsModul .".postAjax('updatePreview')", 'Сгенерировать превью') ?>
  </div>
</div>

<script language="JavaScript">
setTimeout(function() {
    cmf.admin.gallery.init({ handles: true, x1: 50, y1: 50, x2: <?=50+$width ?>, y2: <?=50+$height ?>});
}, 100);
</script>
    <?

	}

}

?>
