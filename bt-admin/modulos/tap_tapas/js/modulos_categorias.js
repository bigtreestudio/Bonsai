// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
function CargarModulo(catcod)
{

	//$.blockUI({ message: '<h1><img src="images/cargando.gif" /> Cargando categoria...</h1>' })	
	var param, url;
	param = "catcod="+catcod;
		$.ajax({
		   type: "POST",
		   url: "tap_tapas_combo_modulo_module.php",
		   data: param,
		   success: function(msg){
			 $("#Modulos").html(msg);
			 //$.unblockUI();
		   }
		 });
	
		return true;
}
$( ".modulo_icono" ).mouseover(function() {
  $( "#nombre_modulo" ).html( $( this ).attr('title'));
});
$( ".modulo_icono" ).mouseout(function() {
  $( "#nombre_modulo" ).html( '&nbsp;');
});
