
$(document).on('click', '.navbar-toggle', function (e) {
	e.preventDefault();
	if (!$($(this).data("target")).hasClass("in"))
		$($(this).data("target")).addClass("in");
	else
		$($(this).data("target")).removeClass("in");
})

var visible;
$(document).on('click', '.dropdown-toggle', function (e) {
	e.preventDefault();
	visible = false;
	if ($(this).parent().hasClass("open"))
		visible = true;
		
	$(".dropdown").removeClass("open");
	
	if (!visible)
		$(this).parent().addClass("open");
})
$(document).click(function(e) {
	if (!$(e.target).is('.dropdown-toggle, .dropdown-toggle *')) {
		$(".dropdown").removeClass("open");
	}
});




function MostraMapa()
{
	$("#mapa_ampliado").toggle();

}

