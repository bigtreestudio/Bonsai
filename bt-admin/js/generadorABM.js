jQuery(document).ready(function(){
	$(".chzn-select").chosen();
});

function BuscarCampos()
{
	BuscarCamposTipo(2,"#camposbusquedaavanzadalst");
	BuscarCamposTipo(3,"#camposListadoAvanzada");
	BuscarCamposTipo(4,"#camposErrores");
	BuscarCamposTipo(5,"#camposAlta");
	
	var param = "tabla="+$("#tabla").val();
	$.ajax({
	   type: "POST",
	   url: "generadorABMCampos.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			jQuery("#campoorden").html(msg);
			jQuery("#campocodigo").html(msg);
			jQuery("#campoestado").html(msg);
			jQuery("#archivonombre").val($("#tabla").val());
			

	   }
	});
}


function Sortable()
{
	$(".ordenCampos").sortable(
	  { 
		tolerance: 'pointer',
		scroll: true , 
		handle: ".orden",
		connectWith: ".sortable_lst",
		axis: 'y',
		cursor: 'pointer',
		opacity: 0.6, 
		update: function() {
			
		}				  
	
	 });

}


function BuscarCamposTipo(tipo,id)
{
	var param = "tabla="+$("#tabla").val();
	param += "&tipo="+tipo;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Buscando...</h1>',baseZ: 9999999999 });
	$.ajax({
	   type: "POST",
	   url: "generadorABMCampos.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			jQuery(id).html(msg);
			if (tipo==5)
			Sortable();
			$.unblockUI();
	   }
	});
}


function Validar()
{
	var Json = false; 
	
	if(!$("#Paquete").is(':checked') && !$("#GenerarEnSitio").is(':checked') )
	{
		alert("Debe seleccionar un tipo a Generar: Generar Paquete, Generar Archivos en el sitio");	
			return false;
	}
	
	if($("#Paquete").is(':checked') && $("#GenerarEnSitio").is(':checked') )
	{
		if(!$("#Clases").is(':checked') && !$("#Listado").is(':checked') && !$("#Alta").is(':checked') )
		{
			alert("Debe seleccionar que tipo genera: Generar Clases, Listado, Alta");	
				return false;
		}
	}
	
	
	if($("#clasefront").is(':checked')) 
	{
		if($("#jsonlistado").is(':checked'))
			Json = true;
		if($("#jsoncodigo").is(':checked'))	
			Json = true;
		
		if(!Json)
		{
			alert("Debe seleccionar un Json a Generar");	
			return false;
		}
	}
	if($("#clasemultimedia").val()!="")
	{
		if($("#prefijomultimedia").val()=="")
		{
			alert("Debe agregar un prefijo de la clase multimedia");	
			return false;
		}	
		if($("#preconfigmultimedia").val()=="")
		{
			alert("Debe agregar un prefijo de configuracion de la clase multimedia");	
			return false;
		}	
	}
	if ($("#tabla").val()=="")
	{
		alert("Debe seleccionar una tabla");	
		return false;
	}
	if ($("#archivonombre").val()=="")
	{
		alert("Debe ingresar un nombre de archivo prefijo");	
		return false;
	}
	if ($("#nombreClase").val()=="")
	{
		alert("Debe ingresar un nombre de Clase");	
		return false;
	}
	
	if ($("#campocodigo").val()=="")
	{
		alert("Debe seleccionar un campo codigo");	
		return false;
	}
	if($("#tipoeliminacion").val()==1)
	{
		if ($("#campoestado").val()=="")
		{
			alert("Si tiene eliminacion Logica, debe seleccionar un campo estado");	
			return false;
		}
	}
	if ($("#tieneorden").val()==1)
	{
		if ($("#campoorden").val()=="")
		{
			alert("Debe seleccionar un campo orden");	
			return false;
		}
	}
	
	if ($("#tienemodificarestado").val()==1)
	{
		if ($("#campoestado").val()=="")
		{
			alert("Debe seleccionar un campo estado");	
			return false;
		}
	}
	
	if(!confirm('Esta seguro que desea generar?')) 
		return false;
		
	
	return true;	
	
}


function ValidarMultimedia()
{

	if($("#nombreTablaMultimedia").val()=="")
	{
			alert("Debe agregar un Nombre de la tabla Multimedia");	
			return false;
	}
	if($("#tablaRelacion").val()=="")
	{
			alert("Debe selecionar un Nombre de la tabla a la que se relaciona");	
			return false;
	}
	if($("#borrarCrearTabla").val()=="")
	{
			alert("Debe selecionar Si Borra la tabla y se regenera");	
			return false;
	}
	
	if($("#codigo").val()=="")
	{
			alert("Debe agregar un Codigo Foreign key");	
			return false;
	}
	if($("#prefijomultimedia").val()=="")
	{
			alert("Debe agregar un Prefijo Tabla");	
			return false;
	}
	if($("#nombreClaseMultimedia").val()=="")
	{
			alert("Debe agregar un Nombre Clase Multimedia");	
			return false;
	}
	if($("#preconfigmultimedia").val()=="")
	{
			alert("Debe agregar un Prefijo Config");	
			return false;
	}
	
	if(!$("#tieneimg").is(':checked') && !$("#tienevideo").is(':checked') && !$("#tieneaudio").is(':checked') && !$("#tienearchivos").is(':checked') && !$("#tienetitulo").is(':checked') && !$("#tienedesc").is(':checked') && !$("#tienehome").is(':checked') && !$("#tieneorden").is(':checked') )
	{
		alert("Debe seleccionar un tipo de configuracion");	
			return false;
	}
	
	if(!confirm('Esta seguro que desea generar?')) 
		return false;
		
	
	return true;	
	
}


function VerificarTipoCampo(el,id)
{

	if ($("#camposaltatipo_"+id).val()==12)
		$("#tablaExterna_"+id).show();	
	else
		$("#tablaExterna_"+id).hide();	
	
}


function VerificarOrden()
{
	if($("#campoorden").val()!="")
		$("#tieneorden").val("1");
	else
		$("#tieneorden").val("0");	

}

function VerificarEstado()
{
	if($("#campoestado").val()!="")
		$("#tienemodificarestado").val("1");
	else
		$("#tienemodificarestado").val("0");	

}

function BuscarCamposTabla(id)
{
	var param = "tabla="+$("#tabla_"+id).val();
	$.ajax({
	   type: "POST",
	   url: "generadorABMCampos.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			jQuery("#campofk_"+id).html(msg);
			jQuery("#campodesc_"+id).html(msg);
			jQuery("#campoestado_"+id).html(msg);

	   }
	});
}


