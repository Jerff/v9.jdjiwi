

//include(core.form.js);
core.form.user = new(function() {
    var user = this;


    user.login = {
        'blur': function() {
//            pre('blur');
        //                $(this).addClass('i-text-error').removeClass('i-text');
        },
        'focus':function() {
//            pre('focus');
        //                $(this).addClass('i-text').removeClass('i-text-error');
        }
    };

});