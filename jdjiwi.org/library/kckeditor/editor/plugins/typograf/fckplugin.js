//fckplugin.js
/*
*  Типограф. 
*  Использует 3 различных типографа:
*  1. класс типографа с сайта http://rmcreative.ru/article/programming/typograph.
*  2. сайт http://www.typograf.ru
*  3. типогрф Артемия Лебедева: http://typograf.artlebedev.ru/
*  Папку с плагином положить в FCK и подключить в конфиге:
*  1.в fckeditor.config.js добавить:
* 	FCKConfig.Plugins.Add( 'typograf2' ) ;
*
* 2. в нужнный тулбар добавить (например "DrupalFull"):
* 	['typograf2'],
*
* @author: Igor V.Hudorozhrov , Click1.ru  (http://Click1.ru),typo@click1.ru
* Вы можете спонсировать проект: Z116207482414
*/


FCKCommands.RegisterCommand
( 
	'typograf',
	new FCKDialogCommand
	(
		FCKLang.DlgMyTypografTitle,
		FCKLang.DlgMyTypografTitle,
		FCKConfig.PluginsPath + 'typograf/typograf.html',
		800,
		600
	)
) ;
var oTypografItem = new FCKToolbarButton
(
	'typograf',
	FCKLang['DlgMyTypografTitle']
);
oTypografItem.IconPath = FCKConfig.PluginsPath + 'typograf/typograf.gif' ;
FCKToolbarItems.RegisterItem('typograf', oTypografItem);



