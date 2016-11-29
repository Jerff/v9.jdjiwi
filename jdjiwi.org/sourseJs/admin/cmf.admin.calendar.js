

//include(cmf-0.1.js);
//include(cmf.admin.js)
cmf.admin.calendar = new(function() {
    var t = this;

    t.dateId = false;
    
    t.cal = Calendar.setup({
        showTime: 24,
        minuteStep: 1,
        onSelect: function(cal) {
            cmf.admin.calendar.updateFields(cal);
            cal.hide()
        },
        onTimeChange: function(cal) {
            cmf.admin.calendar.updateFields(cal);
        }
    });
    
    t.focus = function(input) {
        t.dateId = input.id;
        var date = (new RegExp("([0-9]{2})\.([0-9]{2})\.([0-9]{4}) ([0-9]{2})\:([0-9]{2})")).exec(input.value);
        t.cal.setHours(date[4]);
        t.cal.setMinutes(date[5]);
        date = new Date(date[3], date[2]-1, date[1], date[4], date[5]);
        t.cal.selection.set(date)
        t.cal.moveTo(date)
        t.cal.popup(cmf.getId('datePopup'), 'right');
    }

    t.updateFields = function(cal) {
        var date = cal.selection.get();
        if (date) {
            date = Calendar.intToDate(date);
            date.setHours(cal.getHours());
            date.setMinutes(cal.getMinutes());
            cmf.setValue(t.dateId, Calendar.printDate(date, "%d-%m-%Y %H:%M"));
        }
    };
    
});