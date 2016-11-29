<?php

class view_edit {

//	static public function getFile($id, $jsModul, $fileId, &$form, $name, $element, $option=null, $text) {
//		$file = $form->getValue($element);
//
//
//		$content = '<span id="'. $fileId .'_file">';
//		$content .= $form->html($element, 'style="width:230px;"');
//		$content .= '</span>';
//		$content .= '&nbsp;&nbsp;' . cmfAdminView::onclickType2("{$jsModul}.submit();", $text) . '&nbsp;&nbsp;';
//
//		$content .= '<span id="'. $fileId .'" class="button">';
//		$content .= self::getFileView($id, $jsModul, $form, $name, $element, $option);
//		$content .= '</span>';
//		echo $content .'<input name="'. $fileId .'_option" type="hidden" value="'. htmlspecialchars(serialize($option)) .'">';
//
//	}
//
//	static private function getFileView($id, $jsModul, &$form, $name, $element, $option) {
//		$content = '';
//		if($file = $form->getValue($element)) {
//			$isImage = isset($option['isImage']);
//			$isImageView = isset($option['isImageView']);
//			$path = cAppUrl .'/'. $form->get($element)->getPath() . $file;
//			if($isImage) {
//				if($isImageView) {
//					$content .= '<br><img src="'. $path .'" border="0" hspace="5" vspace="5" align="top"><br>';
//				} else {
//	                   $content .= '<a href="'. $path .'" target="_blank" class="button fancybox" rel="group">'. $file .'</a>';
//				}
//			} else {
//				$content .= '<a href="'. $path .'" target="_blank">'. $file .'</a>';
//			}
//			$content .= '&nbsp;&nbsp;'. self::getFileCommand($id, $jsModul, $name, $element);
//		}
//		return $content;
//	}
//
//	static public function getJsFile($id, $jsModul, $fileId, &$form, $name, $element, $option) {
//		$response = $this->ajax();
//
//		$content =  $form->html($element, 'style="width:200px;"');
//		$response->html($fileId .'_file', $content);
//
//		$content = self::getFileView($id, $jsModul, $form, $name, $element, $option);
//		$response->html($fileId, $content);
//	}
//
//	static protected function getFileCommand($id, $jsModul, $name, $element) {
//		return cmfAdminView::onclickType1("if(confirm('Удалить?')) {$jsModul}.ajax('deleteFile', '$name', '$element', '$id');", '[Удалить]');
//	}
//



    /*  === вывод формы - старт и конце, вывод всех JavaScript функций учавствующий в обработке формы === */
    static public function startForm($hash, $errorId, $action, $jsName) {
        $url = cBaseAdminUrl;
        return <<<HTML
<span id="{$errorId}" class="errorForm"></span>
<form name="{$jsName}" id="{$hash}" action="{$url}" method="post" onsubmit="return {$jsName}.submit();" enctype="multipart/form-data">
<script language="JavaScript">
{$jsName} = new cmfController();
{$jsName}.setUrl('{$action}');
{$jsName}.setName('{$hash}');
{$jsName}.setJsName('{$jsName}');
</script>
HTML;
    }

    static public function endForm($jsName) {
        return '</form>';
    }

    /*  === /вывод формы - старт и конце, вывод всех JavaScript функций учавствующий в обработке формы === */
}

?>
