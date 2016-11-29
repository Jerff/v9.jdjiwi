

cmf.admin.gallery = new(function() {
    var gallery = this;

    gallery.select = function(img, selection) {
        if (!selection.width || !selection.height)
            return;

        $('#x1').val(selection.x1);
        $('#x1').val(selection.x1);
        $('#y1').val(selection.y1);
        $('#x2').val(selection.x2);
        $('#y2').val(selection.y2);
        $('#w').val($('#galleryPhotoId').width());
    }

    gallery.image = false;
    gallery.init = function(res) {
        res.onSelectChange = gallery.select;
        res.show = true;

        if(gallery.image) {
            gallery.image.remove();
        }
        gallery.image = $('img#galleryPhotoId').imgAreaSelect({ instance: true});
        gallery.image.setOptions(res);
    }
    gallery.hide = function() {
        if(gallery.image)
        gallery.image.setOptions({hide:true});
    }
    gallery.show = function() {
        if(gallery.image)
        gallery.image.setOptions({show:true});
    }
    gallery.remove = function() {
        if(gallery.image) {
            gallery.image.setOptions({remove:true, hide:true});
        }
    }


    gallery.hideShow = function(view, content) {
        $(view).click(function() {
            if(style.hideShow(content)) {
                $(this).html('Скрыть превью');
                cmf.admin.gallery.show();
             } else {
                $(this).html('Показать превью');
                cmf.admin.gallery.hide();
           }
        });
        if(style.isHide(content)) {
            cmf.admin.gallery.hide();
        } else {
            cmf.admin.gallery.show();
        }
    }
});