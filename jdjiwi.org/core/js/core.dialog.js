
//include(core.js);
core.dialog = new(function() {
    var dialog = this;

    // {title, text, height}
    //    core.dialog.alert({
    //        title: message.title,
    //        text: message.text
    //    });
    dialog.alert = function(attr) {
        $('#dialog-modal').remove();
        var item = $('<div id="dialog-modal" title="'+ attr.title +'"><p>'+ attr.text +'</p></div>').appendTo('body');
        $(item).dialog({
            resizable: false,
            height: 140,
            modal: true
        });
    };

    // {title, text, height, button, start, close}
    //    core.dialog.confirm({
    //        title: message.title,
    //        text: message.text,
    //        button: 'jkjddf',
    //        start: function() {
    //            alert('start')
    //        },
    //        close: function() {
    //            alert('close')
    //        }
    //    });
    dialog.confirm = function(attr) {
        var item = $('<div id="dialog-confirm" title="'+ attr.title +'"><p>'+ attr.text +'</p></div>').appendTo('body');
        $(item).dialog({
            resizable: false,
            height: attr.height ? attr.height : 140,
            modal: true,
            buttons: {
                button: function() {
                    if(attr.start) attr.start();
                    $( this ).dialog( "close" );
                    item.remove();
                },
                Cancel: function() {
                    if(attr.close) attr.close();
                    $( this ).dialog( "close" );
                    item.remove();
                }
            }
        });
    };

});