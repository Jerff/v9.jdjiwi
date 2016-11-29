

cmf.admin.param = new(function() {
    var param = this;

    param.hideShow = function(id) {
        $('#selectParamView'+ id).click(function() {
            if(style.hideShow('.paramEdit'+ id)) {
                $(this).html('Скрыть свойства');
             } else {
                $(this).html('Показать свойства');
           }
        });
        style.hide('.paramEdit'+ id);
    }
});