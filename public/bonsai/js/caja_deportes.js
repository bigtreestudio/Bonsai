function CargarPosicionesFixtureCaja(id,id_torneo, id_zona, tipovisualizacion)
{
	var nrofecha = $("#torneofecha_"+id).val();
	param = "torneocod="+id_torneo;
	param+="&fecha="+nrofecha;
	param+="&tipovisualizacion="+tipovisualizacion;
	$.ajax({
	   type: "POST",
	   url: "/estadisticas_home_ajax.php",
	   data: param,
	   success: function(msg){ 
			$("#caja_deportes_"+id+" .tablaposicionesfixture").html(msg);	
			mostrarZona(id,id_zona);
			mostrarPosiciones(id,idzona)
	   }
	 });
	return true;	
}
function mostrarZona(id,idzona)
{
	$("#caja_deportes_"+id+" .caja_zona").hide(); //oculto todas las de la clase caja_zona
	$("#caja_deportes_"+id+" #zona_"+idzona).show();//solo muestro la que quiero
}
function mostrarPosiciones(id,idzona,elemento)
{
	$("#caja_deportes_"+id+" .#zona_"+idzona+" .caja_zona_fixture").hide(); //oculto todas las de la clase caja_zona
	$("#caja_deportes_"+id+" .#zona_"+idzona+" .caja_zona_posiciones").show(); //oculto todas las de la clase caja_zona
	$("#caja_deportes_"+id+" .botonpestania").removeClass('seleccionado');
	$(elemento).addClass('seleccionado');
	
}
function mostrarFixture(id,idzona,elemento)
{
	$("#caja_deportes_"+id+" .#zona_"+idzona+" .caja_zona_posiciones").hide(); //oculto todas las de la clase caja_zona
	$("#caja_deportes_"+id+" .#zona_"+idzona+" .caja_zona_fixture").show(); //oculto todas las de la clase caja_zona
	$("#caja_deportes_"+id+" .botonpestania").removeClass('seleccionado');
	$(elemento).addClass('seleccionado');
}