function toogleBox(clickedElement, toggleElement) {
	var text = $("#"+clickedElement.getAttribute('id')).html();
	if ($("#"+toggleElement).is(":hidden")) {
		$("#"+toggleElement).fadeIn("normal");
		//$("#"+clickedElement.getAttribute('id')).html("[&minus;]");
		$("#"+clickedElement.getAttribute('id')).html(text.replace("[+]", "[&minus;]"));
	} else {
		$("#"+toggleElement).fadeOut("normal");
		//$("#"+clickedElement.getAttribute('id')).html("[+]");
			// ACHTUNG: das ist kein "normale" Minus-Zeichen, sondern das längere, welchs mit &minus; erstellt wird!
		$("#"+clickedElement.getAttribute('id')).html(text.replace("[−]", "[+]"));
	}

	return true;
}

function toogleTab(toggleElement) {
	if ($("#"+toggleElement).is(":hidden")) {
		$("#"+toggleElement).fadeIn("normal");
	}
	return true;
}
