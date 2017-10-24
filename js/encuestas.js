function VotarEncuesta(idCargando,FormEncuesta,idReloadValores,idBoton,encuesta)
{
	
	var opcion = $("input[name='opcioncod']:checked");
	if( opcion.length == 0 ) {
		alert("Debe seleccionar al menos una opcion");
		return false;
	} 
	var opcionselect = opcion.val();
	$(FormEncuesta).fadeOut();
	$(idCargando).fadeIn();
	param = "encuestacod="+encuesta;
	param += "&opcioncod="+opcionselect;
	$.ajax({
	   type: "POST",
	   url: "/encuesta/responder/ajax",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		 if (msg.Error==false)
		 {
			RecargarResultados(idReloadValores,idCargando,encuesta);
			return true;	 
		 }else
		 {
			 $(idCargando).hide();
			$(FormEncuesta).show();
			alert("Ha ocurrido un error al votar, por favor intente en otro momento.");
			return false;	 
		 }
		 
	   }
	 });
	return true;
}


function RecargarResultados(idReloadValores,idCargando,encuesta)
{
	param = "codigo="+encuesta;
	$.ajax({
	   type: "POST",
	   url: "/encuesta/reload/ajax",
	   data: param,
	   dataType:"html",
	   success: function(msg){
		 $(idReloadValores).html(msg).fadeIn();
		 alert("Usted ha votado correctamente.");
		 $(idCargando).fadeOut();
	   }
	 });
	return true;
}

function VotoRealizado()
{
	alert("Usted ya ha votado la encuesta");
	return false;	
}