

cmf.admin.menu = new(function() {
	var t = this;


	t.open = function (id) {
		$("#"+ id).slideToggle("normal");
	}

	t.select = function(id1, id2, id3) {
		$("#menu"+ id1).slideDown("normal");
		$('div', $("#mainMenu1")).each(function() {
			if(this.id.indexOf('mainMenu')!=-1) {
				var item = this.id.replace('mainMenu', '');
				if(item==id1) {
	                $(this).addClass('botton_tem1');
	                $(this).removeClass('botton_very_tem');
	                t.select1(id1, id2, id3);
				} else {
	                $(this).removeClass('botton_tem1');
	                $(this).addClass('botton_very_tem');
	                t.select1(item, id2, id3);
				}
			}
		});
	}

	t.select1 = function(id1, id2, id3) {
		$('div', $('#menu'+ id1)).each(function() {
			if($(this).attr('rel')!=id1) return;
			if(this.id==id2) {
	            if(t.select2(id1, id2, id3)) {
		            $(this).addClass('botton_tem_bottom1');
		            $(this).removeClass('botton_tem');
		            $('span', this).removeClass('cmfHide');
	            }
			} else {
	            $(this).removeClass('botton_tem_bottom1');
	            $(this).addClass('botton_tem');
	            $('span', this).addClass('cmfHide');
	            t.select2(id1, this.id, id3)
			}
		});
	}

	t.select2 = function(id1, id2, id3) {
		res = true;
		$('div', $('#menu'+ id2)).each(function() {
			res = false;
			if(this.id==id3) {
	            $(this).addClass('botton_tem_bottom2');
	            $(this).removeClass('botton_svetlo');
	            $('span', this).removeClass('cmfHide');
			} else {
	            $(this).removeClass('botton_tem_bottom2');
	            $(this).addClass('botton_svetlo');
	            $('span', this).addClass('cmfHide');
			}
		});
		return res;
	}

});