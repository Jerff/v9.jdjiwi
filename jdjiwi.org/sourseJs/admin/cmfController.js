function cmfController() {
	var t = this;
	var name = '';
	var url = '';
	var jsName = '';


	t.setName = function(n) {
		t.name = n;
	}
	t.getName = function() {
		return t.name;
	}

	t.setUrl = function(n) {
		t.url = n;
	}
	t.getUrl = function() {
		return t.url;
	}

	t.getFormId = function() {
		return 'form_' + t.getName() + '_'+ t.getJsName();
	}
	t.getTable = function() {
		return '#'+ t.getFormId() + ' > table:first';
	}


	t.setJsName = function(n) {
		t.jsName = n;
	}
	t.getJsName = function() {
		return t.jsName;
	}


	t.isBlok = false;
	t.isSend = function() {
		if(t.isBlok) return false;
		t.isBlok = true;
		setTimeout(function() {
		    t.isBlok = false;
		}, 500);
		return true;
	}
	t.submit = function() {
		return t.postAjax('update');
	}
	t.ajax = function() {
		return t.send({arg: arguments});
	}
	t.postAjax = function() {
		if(!t.isSend()) return;
		return t.send({arg: arguments, isForm: true});
	}
	t.postAjaxSave = function() {
		if(!t.isSend()) return;
		return t.send({arg: arguments, isForm: true, isSave: true});
	}
	t.serialize = function(arg) {
		var arg2 = new Array();
		for (i = 0; i < arg.length; i++) {
	       arg2[i] = arg[i];
		}
		return JSON.stringify(arg2);
	}


	t.send = function(arg) {
		var send = {isRunController: true, ajaxArg: t.serialize(arg.arg), ajaxName: t.getName(), ajaxJsName: t.getJsName(), ajaxUrl: t.getUrl()};
		if(arg.isSave) {
			send.save = 1;
		}
		if(arg.isForm) {
			send.form = cmf.getId(t.getFormId())
		}

	    return cmf.ajax.send(cmf.ajax.getUrl(), send);
	}



	t.moveMoveAjax = function(id, name, key) {
		if(id.moveMoveAjax2 = !id.moveMoveAjax2) {
            style.hide('#'+ name +'posView'+ key);
			style.show('#'+ name +'posText'+ key);
		} else {
			style.show('#'+ name +'posView'+ key);
			style.hide('#'+ name +'posText'+ key);

			var pos = name +'pos'+ key;
			var old = name +'posOld'+ key;

			posV = cmf.getValue(pos);
			oldV = cmf.getValue(old);
			if(posV!=oldV) {
				t.ajax('moveMoveAjax', key, posV);
				cmf.setValue(old, posV);
			}
		}
	}


	t.move1Ajax = function(key, type) {
		t.ajax('move1', key, type);
	}
	t.move2Ajax = function(key, type) {
		t.ajax('move2', key, type);
	}
	t.move1 = function(id, id2) {
        var line = cmf.getId(id);
        var row = line.parentNode.rows[line.rowIndex-1];
        if(row) {
        	line.parentNode.insertBefore(line.parentNode.rows[line.rowIndex], row);
        	t.resetViewTable();
        }
	}
	t.move2 = function(id, id2) {
        var line = cmf.getId(id);
        var row = line.parentNode.rows[line.rowIndex+1];
        if(row) {
        	line.parentNode.insertBefore(row, line.parentNode.rows[line.rowIndex]);
        	t.resetViewTable();
        }
	}








	t.deleteAjax = function(line, key) {
		if(!confirm('Удалить?')) return;
		if(key==0) {
			while(line.tagName.toUpperCase()!='TR')
				line = line.parentNode;
			line.parentNode.removeChild(line);
			t.resetViewTable();
		} else {
			t.ajax('deleteRecord', key);
		}
	}

	t.deleteLine = function(id) {
		var line = cmf.getId(id);
		line.parentNode.removeChild(line);
		t.resetViewTable();
	}

	t.resetViewTable = function() {
        $(t.getTable() + ' > tbody > tr:gt(1):even').attr('class', 'columnn_svet2');
		$(t.getTable() + ' > tbody > tr:gt(1):odd').attr('class', 'columnn_svet');
	}


	t.initLine = function() {
		if($(t.getTable()).attr('class')!='great_table') return;
		$(t.getTable() + ' > tbody > tr:eq(1)').hide();
		t.resetViewTable();
	}
	t.newLine = function(index, data) {
		var tr = $(t.getTable() + ' > tbody > tr').get(1).cloneNode(true);
		t.newLineSetIndex(tr, index);
		$(t.getTable() + ' > tbody').append(tr);
		t.resetViewTable();

		if(t.addNewLine) {
			$('#' + tr.id + ' > td').each(
				function(index) {
					var r = t.addNewLine(index, data);
					if(r) this.innerHTML = r;
				}
			);
		}
	}

	t.newLineSetIndex = function(obj, index) {
        if(obj.childNodes)
        	if(obj.childNodes.length) {
	        	for(var i = 0; i<obj.childNodes.length; i++) {
	        		t.newLineSetIndex(obj.childNodes[i], index);
	        	}
        	}
        if($(obj).attr('id')) {
        	$(obj).attr('id', $(obj).attr('id').replace('_0_', index));
        	$(obj).attr('id', $(obj).attr('id').replace('{0}', index));
        }
        if($(obj).attr('name')) {
        	$(obj).attr('name', $(obj).attr('name').replace('_0_', index));
        	$(obj).attr('name', $(obj).attr('name').replace('{0}', index));
        }
        $(obj).attr('keyIndex', index);
	}

}