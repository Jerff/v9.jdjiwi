<?php

cLoader::library('wysiwyng/cmfWysiwyngKCKeditor');
class cmfWysiwyngConfig extends cmfWysiwyngKCKeditor {


	static public function &getMap() {
		$_map = array(
		//'section'=>	'catalog_section_edit_controller',
		'product'=>	      'product_edit_controller',
		'catalog/size'=>  'catalog_size_edit_controller',

		'showcase'=>	  'showcase_list_config_controller',

        'subscribe'=>     'subscribe_edit_controller',

		'main'=>	      'main_info_controller',
		'info'=>	      'content_info_edit_controller',
		'content'=>	      'content_content_edit_controller',
		'content/pages'=> 'content_pages_edit_controller',
		'static'=>	      'content_static_edit_controller',

		'news'=>	      array(path_news, 'news_edit_controller'),
		'article'=>	      array(path_article, 'article_edit_controller'),
		'photo'=>	      array(path_photo, 'photo_edit_controller'),

		'payment/config'=>'payment_config_controller',

		'mail/templates'=>'_mail_templates_edit_controller',
		);
		return $_map;
	}

}

?>