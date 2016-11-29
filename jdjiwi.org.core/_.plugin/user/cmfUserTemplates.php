<?php

class cmfUserTemplates {

	static public function getAdress(&$userAdress, &$form, $id) {

?>

		<div class="clear"><!- -></div>
		<div id="contact<?=$id ?>">
	<?=$userAdress->startForm('class="simpleform"') ?>

		<br>
		<div class="text"><b>Адрес доставки:</b></div>
		<div class="clear"><!- -></div>
		<div class="text"><?=$userAdress->formError() ?></div>
		<div class="text"><?=$userAdress->formSave() ?></div>
		<div class="clear"><!- -></div>
		<input name="id" type="hidden" value="<?=$id ?>">

		<label class="label">Город:</label><?=$form->html('gorod', 'onchange="cmfContactSity(this.value, \''. $form->getName('metro') .'\')"') ?>
		<div class="clear"><!- -></div>
		<label class="label">Ближайшее метро:</label><?=$form->html('metro') ?>
		<div class="clear"><!- -></div>

		<div class="text"><?=$form->getHtmlError('adress') ?></div>
		<div class="clear"><!- -></div>
		<label class="label">Адресс:</label><?=$form->html('adress') ?>
		<div class="clear"><!- -></div>

		<div class="text"><?=$form->getHtmlError('notice') ?></div>
		<div class="clear"><!- -></div>
		<label class="label">Комментарии:</label><?=$form->html('notice') ?>
		<div class="clear"><!- -></div>

		<input type="submit" class="submit2" value="Сохранить" />
		<div name="adressDel" id="adressDel<?=$id ?>"><input onclick="return cmfUserAdressDel(<?=$id ?>);" type="submit" class="submit3 cHide" value="Удалить" /></div>

	<?=$userAdress->endForm() ?>
		</div>
<?
	}
}

?>