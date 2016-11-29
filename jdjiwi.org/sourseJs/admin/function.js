



// управления сроками
function cmfDeleteChildTag(line, tag) {
	while(line.tagName.toUpperCase()!=tag)
		line = line.parentNode;
	line.parentNode.removeChild(line);
}
function cmfDeleteChildTag(line) {
	line.parentNode.removeChild(line);
}
function cmfGetChildTag(line, tag) {
	while(line.tagName.toUpperCase()!=tag)
		line = line.parentNode;
	return line;
}




function tagCahangeOnclick(textarea, id) {
	var text = $(id).text();
	if($cmf.getId(textarea).value.indexOf(text)==-1) {
       if(!$cmf.getId(textarea).value) {
	       $cmf.getId(textarea).value += text;
       } else {
	       $cmf.getId(textarea).value += ', '+ text;
       }
	}
}