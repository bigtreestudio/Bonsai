var menuPadding = 0;
var paddingTo = 5;

$(document).ready(function() {

});


function LetterRoll(id,txt)
{
	var s = $("#"+id);
	var type_this = txt;
	var index = 0;
	window.next_letter = function() {
		if (index <= type_this.length) {
			s.attr("placeholder",type_this.substr(0, index++));
			setTimeout("next_letter()", 80);
		}
	}
	next_letter();
}

function bindReady(handler){
	var called = false

	function ready() { 
		if (called) return
		called = true
		handler()
	}

	if ( document.addEventListener ) { // native event
		document.addEventListener( "DOMContentLoaded", ready, false )
	} else if ( document.attachEvent ) {  // IE

		try {
			var isFrame = window.frameElement != null
		} catch(e) {}

		// IE, the document is not inside a frame
		if ( document.documentElement.doScroll && !isFrame ) {
			function tryScroll(){
				if (called) return
				try {
					document.documentElement.doScroll("left")
					ready()
				} catch(e) {
					setTimeout(tryScroll, 10)
				}
			}
			tryScroll()
		}

		// IE, the document is inside a frame
		document.attachEvent("onreadystatechange", function(){
			if ( document.readyState === "complete" ) {
				ready()
			}
		})
	}

	// Old browsers
	if (window.addEventListener)
		window.addEventListener('load', ready, false)
	else if (window.attachEvent)
		window.attachEvent('onload', ready)
	else {
		var fn = window.onload // very old browser, copy old onload
		window.onload = function() { // replace by new onload and call the old one
			fn && fn()
			ready()
		}
	}
}

function listo()
{
    $( window ).scroll(checkScroll);
}
function FijarDesfijarBloque(bloque, alto, tipo, addClass, classBloque){
		$(bloque).css("position",tipo).css("top",alto);
		if (addClass)
			$(bloque).addClass(classBloque);
		else
			$(bloque).removeClass(classBloque);
}

var $AltoMenu = 30;
function checkScroll()
{	
	if($(window).scrollTop() > $AltoMenu) {
	  $(".secondary").addClass("visible");
	  $(".navbar-brand img").hide();
	  $(".menuppal").hide();

	} else {
	  $(".secondary").removeClass("visible");
	   $(".navbar-brand img").show();
	   $(".menuppal").show();
	}
}


bindReady(listo);




