<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");


$this->assing('title', 'Админ панель');


cmfHeader::setJs('/core-admin/plugin/jquery-1.8.3.min.js');
cmfHeader::setJs('/core-admin/plugin/jquery.ba-hashchange.min.js');

//jquery.pnotify
cmfHeader::setJs('/core-admin/base-admin/js/bootstrap.min.js');
cmfHeader::setCss('/core-admin/base-admin/css/bootstrap.css');
cmfHeader::setCss('/core-admin/base-admin/css/bootstrap-responsive.css');

//fancyapps
cmfHeader::setJs('/core-admin/plugin/fancyapps/jquery.fancybox.pack.js');
cmfHeader::setCss('/core-admin/plugin/fancyapps/jquery.fancybox.css');

//jquery-ui
cmfHeader::setJs('/core-admin/plugin/jquery-ui/jquery-ui-1.10.0.custom.min.js');
cmfHeader::setCss('/core-admin/plugin/jquery-ui/cupertino/jquery-ui-1.10.0.custom.css');

//jquery.pnotify
cmfHeader::setJs('/core-admin/plugin/jquery.pnotify/jquery.pnotify.min.js');
cmfHeader::setCss('/core-admin/plugin/jquery.pnotify/jquery.pnotify.css');


// ядро
cmfHeader::setJs('/core/js/function.js');
cmfHeader::setJs('/core/js/core.js');
cmfHeader::setJs('/core/js/core.config.js');
cmfHeader::setJs('/core/js/core.dialog.js');
cmfHeader::setJs('/core/js/core.form.js');
cmfHeader::setJs('/core/js/core.ajax.js');

cmfHeader::setJs('/core-admin/js/core.ajax.controller.js');
cmfHeader::setJs('/core-admin/js/loadBrowser.js');
cmfHeader::setJs('/core-admin/js/core.init.js');

cmfHeader::setCss('/core/css/core.css');
cmfHeader::setCss('/core/css/core-form.css');


return 1;


cmfHeader::setJs('/sourseJs/jquery-1.5.1.min.js');
cmfHeader::setJs('/sourseJs/json.min.js');
cmfHeader::setJs('/sourseJs/include/jquery.ba-hashchange.min.js');
cmfHeader::setJs('/sourseJs/JsHttpRequest.min.js');


cmfHeader::setJs('/sourseJs/jquery.fancybox/jquery.fancybox-1.3.4.pack.js');
cmfHeader::setCss('/sourseJs/jquery.fancybox/jquery.fancybox-1.3.4.css');


cmfHeader::setJs('/sourseJs/colorpicker/js/colorpicker.js');
cmfHeader::setCss('/sourseJs/colorpicker/css/colorpicker.css');


cmfHeader::setJs('/sourseJs/ui/jquery-ui-1.8.13.custom.min.js');
cmfHeader::setCss('/sourseJs/ui/jquery-ui-1.8.13.custom.css');

cmfHeader::setJs('/sourseJs/sourse/jquery.corner.js');


cmfHeader::setJs('/sourseJs/jquery.imgareaselect/scripts/jquery.imgareaselect.pack.js');
cmfHeader::setCss('/sourseJs/jquery.imgareaselect/css/imgareaselect-default.css');


cmfHeader::setJs('/sourseJs/JSCal/js/jscal2.js');
cmfHeader::setJs('/sourseJs/JSCal/js/lang/ru.js');
cmfHeader::setCss('/sourseJs/JSCal/css/jscal2.css');
cmfHeader::setCss('/sourseJs/JSCal/css/border-radius.css');
cmfHeader::setCss('/sourseJs/JSCal/css/gold/gold.css');


cmfHeader::setJs('/sourseJs/cmf-0.1.js');
cmfHeader::setJs('/sourseJs/cmf.ajax.js');
cmfHeader::setJs('/sourseJs/cmf.ajax.driver.js');
cmfHeader::setJs('/sourseJs/cmf.style.js');
cmfHeader::setJs('/sourseJs/cmf.form.js');
cmfHeader::setJs('/sourseJs/function.js');


cmfHeader::setJs('/sourseJs/admin/cmfController.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.menu.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.gallery.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.param.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.calendar.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.ajax.js');
cmfHeader::setJs('/sourseJs/admin/cmf.admin.ajax.driver.js');

cmfHeader::setJs('/sourseJs/admin/function.js');
cmfHeader::setJs('/sourseJs/admin/functionWysiwing.js');



cmfHeader::setCss('/sourseCss/admin/styles.css');
cmfHeader::setCss('/sourseCss/cmfStyle.css');


cmfHeader::setJs('/sourseJs/admin/loadDocument.js');

?>