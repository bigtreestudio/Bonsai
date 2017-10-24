 $(document).ready(function() {
      $('.progress .progress-bar').css("width",
                function() {
                    return $(this).attr("aria-valuenow") + "%";
                }
        )
 });
 
 function SeleccionarEtapa(nro)
 {
	$(".detalle_etapa").hide();
	$('.menu_compromiso_item').each(function(i) {
		$(this).removeClass('seleccionado');
  	});
	$("#menu_compromiso_item_"+nro).addClass('seleccionado');
	$("#detalle_compromiso_"+nro).show();
	return true;
 }