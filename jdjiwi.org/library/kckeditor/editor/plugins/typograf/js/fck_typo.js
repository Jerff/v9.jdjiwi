var oEditor=window.parent.InnerDialogLoaded();var FCK=oEditor.FCK;var FCKLang=oEditor.FCKLang;var FCKConfig=oEditor.FCKConfig;var variable=null;var textEdit=null;var textView=null;var textOut=null;var zgIn=null;var zgOut=null;var btnEdit=null;var btnView=null;function urlencode(str){var histogram={},histogram_r={},code=0,tmp_arr=[];var ret=str.toString();var replacer=function(search,replace,str){var tmp_arr=[];tmp_arr=str.split(search);return tmp_arr.join(replace);};histogram['!']='%21';histogram['%20']='+';ret=encodeURIComponent(ret);for(search in histogram){replace=histogram[search];ret=replacer(search,replace,ret)}
return ret.replace(/(\%([a-z0-9]{2}))/g,function(full,m1,m2){return"%"+m2.toUpperCase();});return ret;}
function OnLoad()
{textEdit=document.getElementById('txtEditTypo');textView=document.getElementById('txtViewTypo');textOut=document.getElementById('txtOutTypo');btnEdit=document.getElementById('btnEdit');btnView=document.getElementById('btnView');zgIn=document.getElementById('zgIn');zgOut=document.getElementById('zgOut');oEditor.FCKLanguageManager.TranslatePage(document);textView.style.display='block';textEdit.style.display='none';btnView.style.display='none';zgIn.style.display='block';zgOut.style.display='none';textView.innerHTML=FCK.GetXHTML();textEdit.value=FCK.GetXHTML();}
function typo_text(){window.parent.SetOkButton(true);variable=urlencode(textView.innerHTML);if(document.form1.radio[0].checked){$.post(oEditor.FCKConfig.PluginsPath+'typograf/php/typo_offline.php',{text:variable},show_typo);}
if(document.form1.radio[1].checked){$.post(oEditor.FCKConfig.PluginsPath+'typograf/php/typograf_ru.php',{text:variable},show_typo);}
if(document.form1.radio[2].checked){$.post(oEditor.FCKConfig.PluginsPath+'typograf/php/typograf_al.php',{text:variable},show_typo);}
textOut.style.display='block';textOut.innerHTML = '<div style="text-align: center; padding: 50px 0 0;">Подождите, идёт обработка текста...<br /><br /><img src="'+oEditor.FCKConfig.PluginsPath+'typograf/loader.gif"></div>';textView.style.display='none';textEdit.style.display='none';btnView.style.display='';btnEdit.style.display='none';zgIn.style.display='none';zgOut.style.display='block';}
function edit_text(){textEdit.style.display='block';textEdit.value=textView.innerHTML;textView.style.display='none';textOut.style.display='none';btnView.style.display='';btnEdit.style.display='none';zgIn.style.display='block';zgOut.style.display='none';}
function view_text(){textView.innerHTML=textEdit.value;textOut.style.display='none';textView.style.display='block';textEdit.style.display='none';btnView.style.display='none';btnEdit.style.display='';zgIn.style.display='block';zgOut.style.display='none';}
function show_typo(textOut){$('#txtOutTypo').html(textOut);}
function Ok()
{FCK.Focus();var B=FCK.SetHTML($('#txtOutTypo').html());window.parent.Cancel(true);}
var oRange=null;

