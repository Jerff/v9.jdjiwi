<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");


$this->assing('title', 'Админ панель');


cHeader::addMeta(['http-equiv', 'pragma'], ['content', 'no-cache']);
cHeader::addMeta(['http-equiv', 'cache-control'], ['content', 'no-cache']);

cHeader::addJs('/core-admin/plugin/jquery-2.0.3.min.js');
cHeader::addString('
        <!--[if lt IE 9]>
          <script type="text/javascript" src="/core-admin/plugin/jquery-1.10.2.min.js"></script>
        <![endif]-->');
cHeader::addJs('/core-admin/plugin/jquery.cookie-1.4.min.js');
cHeader::addJs('/core-admin/plugin/jquery.ba-hashchange.min.js');

//bootstrap
cHeader::addJs('/core-admin/base/js/bootstrap.min.js');
cHeader::addCss('/core-admin/base/css/bootstrap.css');
cHeader::addCss('/core-admin/base/css/bootstrap-responsive.css');

//fancyapps
cHeader::addJs('/core-admin/plugin/fancyapps/jquery.fancybox.pack.js');
cHeader::addCss('/core-admin/plugin/fancyapps/jquery.fancybox.css');

//jquery-ui
cHeader::addJs('/core-admin/plugin/jquery-ui/jquery-ui-1.10.0.custom.min.js');
cHeader::addCss('/core-admin/plugin/jquery-ui/cupertino/jquery-ui-1.10.0.custom.css');

//jquery.pnotify
cHeader::addJs('/core-admin/plugin/jquery.pnotify/jquery.pnotify.min.js');
cHeader::addCss('/core-admin/plugin/jquery.pnotify/jquery.pnotify.css');


// ядро
cHeader::addJs('/core/js/function.js');
cHeader::addJs('/core/js/core.js');
cHeader::addJs('/core/js/core.config.js');
cHeader::addJs('/core/js/core.dialog.js');
cHeader::addJs('/core/js/core.form.js');
cHeader::addJs('/core/js/core.ajax.js');

cHeader::addJs('/core-admin/js/core.ajax.controller.js');
cHeader::addJs('/core-admin/js/loadBrowser.js');
cHeader::addJs('/core-admin/js/core.init.js');

cHeader::addCss('/core/css/core.css');
cHeader::addCss('/core/css/core-form.css');

cHeader::addString('
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->');
return 1;


cHeader::addJs('/sourseJs/jquery-1.5.1.min.js');
cHeader::addJs('/sourseJs/json.min.js');
cHeader::addJs('/sourseJs/include/jquery.ba-hashchange.min.js');
cHeader::addJs('/sourseJs/JsHttpRequest.min.js');


cHeader::addJs('/sourseJs/jquery.fancybox/jquery.fancybox-1.3.4.pack.js');
cHeader::addCss('/sourseJs/jquery.fancybox/jquery.fancybox-1.3.4.css');


cHeader::addJs('/sourseJs/colorpicker/js/colorpicker.js');
cHeader::addCss('/sourseJs/colorpicker/css/colorpicker.css');


cHeader::addJs('/sourseJs/ui/jquery-ui-1.8.13.custom.min.js');
cHeader::addCss('/sourseJs/ui/jquery-ui-1.8.13.custom.css');

cHeader::addJs('/sourseJs/sourse/jquery.corner.js');


cHeader::addJs('/sourseJs/jquery.imgareaselect/scripts/jquery.imgareaselect.pack.js');
cHeader::addCss('/sourseJs/jquery.imgareaselect/css/imgareaselect-default.css');


cHeader::addJs('/sourseJs/JSCal/js/jscal2.js');
cHeader::addJs('/sourseJs/JSCal/js/lang/ru.js');
cHeader::addCss('/sourseJs/JSCal/css/jscal2.css');
cHeader::addCss('/sourseJs/JSCal/css/border-radius.css');
cHeader::addCss('/sourseJs/JSCal/css/gold/gold.css');


cHeader::addJs('/sourseJs/cmf-0.1.js');
cHeader::addJs('/sourseJs/cmf.ajax.js');
cHeader::addJs('/sourseJs/cmf.ajax.driver.js');
cHeader::addJs('/sourseJs/cmf.style.js');
cHeader::addJs('/sourseJs/cmf.form.js');
cHeader::addJs('/sourseJs/function.js');


cHeader::addJs('/sourseJs/admin/cmfController.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.menu.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.gallery.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.param.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.calendar.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.ajax.js');
cHeader::addJs('/sourseJs/admin/cmf.admin.ajax.driver.js');

cHeader::addJs('/sourseJs/admin/function.js');
cHeader::addJs('/sourseJs/admin/functionWysiwing.js');



cHeader::addCss('/sourseCss/admin/styles.css');
cHeader::addCss('/sourseCss/cmfStyle.css');


cHeader::addJs('/sourseJs/admin/loadDocument.js');

?>