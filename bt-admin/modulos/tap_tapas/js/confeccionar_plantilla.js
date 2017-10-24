// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	MovimientoMacros();
});


function MovimientoMacros()
{	
	$(".macros").sortable(
	  { 
		tolerance: 'pointer',
		scroll: true , 
		handle: $(".modules_move"),
		connectWith: '.macros',
		placeholder: "placeholderzona",
		cursor: 'pointer',
		opacity: 0.6, 
		update: function() {
			
				var areacod = $(this).attr("id").substring(5);
				var order = $(this).sortable("serialize")+"&areacod="+areacod+"&plantcod="+$("#plantcod").val()+"&accion=4"; 
				console.log("Orden:"+order);
				$("#msgGuardando").html("Guardando...").show();
				$.post("tap_plantillas_confeccionar_carga_zonas_upd.php", order, function(msg){
					
					if (msg.IsSuccess)
					{
						$("#msgGuardando").html("Guardado").fadeOut(1000);
					}else
					{
						alert(msg.Msg)	
					}
				}, "json");
			
		}				  
	});
	$(".macros").disableSelection();
}



function CargarNuevoMacro(plantcod)
{
	var param, url;
	param = "plantcod="+plantcod;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_zonas.php",
	   data: param,
	   success: function(msg){
		   
			$("#PopupCargaZona").dialog({
				width: 800, 
				zIndex: 999999999,
				position: 'top', 
				resizable: true,
				modal:true,
				title: "Cargar Nuevo Macro", 
				open: function(type, data) {$("#PopupCargaZona").html(msg);}
			});
	   }
	 });
}

function CargarMacro(plantcod)
{
	var param, url;
	param = "plantcod="+plantcod;
	param += "&macrocod="+$("#macrocod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_zonas.php",
	   data: param,
	   success: function(msg){
			$("#CargaMacro").html(msg);
			MovimientoMacroSeleccionado();
	   }
	 });
}



var id = "";
function MovimientoMacroSeleccionado()
{	
	$(".selmacro").sortable(
	  { 
	  	tolerance: 'pointer',
	  	scroll: true , 
	  	zIndex : 99999,
	  	handle: $(".zonascargadas"),
		placeholder: "placeholderzona",
	  	connectWith: '.macros',
		cursor: 'pointer', 
		opacity: 0.6, 
		helper: function() {
			
			helper = $('<div>')
				.appendTo( '.macros' )
				.css( { 'z-index': 9999 } ).html($(".selmacro").html());
			return helper;
		},
		update: function(event, ui) {

			position_updated = false;
			var areacod = $(ui.item).parent().attr("id").substring(5);
			$("#PopupCargaZona").dialog("close");
			var macro = $("#plantmacrocod_0").attr("rel");
			var macrocod = macro.substr(6);
			var order = $(".macros").sortable("serialize");
			AgregarMacro(macrocod,areacod);
		},			  		
		start: function( event, ui ) {
			ui.helper.text( ui.item );
		}

	});
}


function AgregarMacro(macro,areacod)
{
	param  = "plantcod="+$("#plantcod").val(); 
	param += "&macrocod="+macro;
	param += "&areacod="+areacod;
	param += "&accion=1";
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Agregando macro...</h1>',baseZ: 9999999999 })	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_zonas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{

				$("#plantmacrocod_0").replaceWith(msg.htmlgenerado);
				var order = $("#area_"+areacod+".macros").sortable("serialize")+"&areacod="+areacod+"&plantcod="+$("#plantcod").val()+"&accion=4";
				MovimientoMacros();
				$.post("tap_plantillas_confeccionar_carga_zonas_upd.php", order, function(msg){
					
					if (msg.IsSuccess)
					{
						$.unblockUI();
					}else
					{
						alert(msg.Msg)	
					}
				}, "json");				
			}else
			{
				alert(msg.Msg);	
			}
	   }
	});
}




function EliminarMacro(plantmacrocod)
{
	if (!confirm("Esta seguro que desea eliminar el macro?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Eliminando macro...</h1>',baseZ: 9999999999 })	
	param  = "plantcod="+$("#plantcod").val(); 
	param += "&plantmacrocod="+plantmacrocod;
	param += "&accion=3";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_zonas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				$.unblockUI();
				$("#plantmacrocod_"+plantmacrocod).fadeOut(300,function() {
					$("#plantmacrocod_"+plantmacrocod).remove();
				});
			}else
			{
				alert(msg.Msg);	
				$.unblockUI();
			}
	   }
	});
}



function EditarMacro(plantmacrocod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Cargando datos...</h1>',baseZ: 9999999999 })	
	param  = "plantcod="+$("#plantcod").val(); 
	param += "&plantmacrocod="+plantmacrocod;
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_edit_macro.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#PopupCargaZona").dialog({
				width: 800, 
				zIndex: 999999999,
				position: 'top', 
				resizable: true,
				modal:true,
				title: "Editar Macro", 
				open: function(type, data) {$("#PopupCargaZona").html(msg);$.unblockUI();}
			});
	   }
	});
}


function GuardarDatosMacro(plantmacrocod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Modificando macro...</h1>',baseZ: 9999999999 })	
	param = $("#formmacro").serialize();
	param += "&plantcod="+$("#plantcod").val(); 
	param += "&accion=2";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_zonas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				
				$("#plantmacrocod_"+plantmacrocod).fadeOut(100,function() {
					$("#plantmacrocod_"+plantmacrocod).replaceWith(msg.htmlgenerado);
				});
				$("#PopupCargaZona").dialog('close');
				$.unblockUI();
			}else
			{
				alert(msg.Msg);	
				$.unblockUI();
			}
	   }
	});
}



function ModalAgregarColumna(macrozonacod,plantmacrocod)
{
	$.blockUI({ message: '<div style="font-size:16px; font-weight:bold"><img src="images/cargando.gif" />Cargando estructuras del macro...</h1>',baseZ: 9999999999 })	
	var param, url;
	param = "macrozonacod="+macrozonacod;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_columnas.php",
	   data: param,
	   success: function(msg){
		   
			$("#PopupCargaZona").dialog({
				width: 800, 
				zIndex: 999999999,
				position: 'top', 
				resizable: true,
				modal:true,
				title: "Cargar Nueva Columna en Macro: "+plantmacrocod, 
				open: function(type, data) {$("#PopupCargaZona").html(msg);$.unblockUI();}
			});
	   }
	 });
}

function AgregarColumna(macrozonacod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Agregando columna...</h1>',baseZ: 9999999999 })	
	var param, url;
	param = $("#formcolumnaagregar").serialize();
	param += "&accion=1";
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_columnas_upd.php",
	   data: param,
	   success: function(msg){
		   	$("#PopupCargaZona").dialog("close");
		   	CarcarColumnas(macrozonacod);
	   }
	 });
}

function CarcarColumnas(macrozonacod)
{
	var param, url;
	param = "macrozonacod="+macrozonacod;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_columnas_recarga.php",
	   data: param,
	   success: function(msg){
		   $("#macrozonacod_"+macrozonacod).html(msg);
			CargarMovimientoMacro(macrozonacod);
			$.unblockUI();
	   }
	 });
}


function CargarMovimientoMacro(macrozonacod)
{
	$("#macrozonacod_"+macrozonacod).sortable(
	  { 
	  	tolerance: 'pointer',
	  	scroll: true , 
	  	zIndex : 99999,
	  	handle: $(".modules_move_column"),
		placeholder: "placeholderzona",
	  	connectWith: "#macrozonacod_"+macrozonacod,
		cursor: 'pointer', 
		opacity: 0.6, 
		update: function() {
			var order = $(this).sortable("serialize")+"&macrozonacod="+macrozonacod+"&accion=4"; 
			$("#msgGuardando").html("Guardando...").show();
			$.post("tap_plantillas_confeccionar_carga_columnas_upd.php", order, function(msg){
				
				if (msg.IsSuccess)
				{
					$("#msgGuardando").html("Guardado").fadeOut(1000);
				}else
				{
					alert(msg.Msg)	
				}
			}, "json");
		}				  
	});
}


function EliminarColumna(plantmacrocolumnacod)
{
	if (!confirm("Esta seguro que desea eliminar las columnas?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Eliminando columnas...</h1>',baseZ: 9999999999 })	
	param  = "plantcod="+$("#plantcod").val(); 
	param += "&plantmacrocolumnacod="+plantmacrocolumnacod;
	param += "&accion=3";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_columnas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				$.unblockUI();
				$("#plantmacrocolumnacod_"+plantmacrocolumnacod).fadeOut(300,function() {
					$("#plantmacrocolumnacod_"+plantmacrocolumnacod).remove();
				});
			}else
			{
				alert(msg.Msg);	
				$.unblockUI();
			}
	   }
	});
}




function EditarColumna(zonacod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Cargando datos...</h1>',baseZ: 9999999999 })	
	param  = "plantcod="+$("#plantcod").val(); 
	param += "&zonacod="+zonacod;
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_edit_columna.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#PopupCargaZona").dialog({
				width: 800, 
				zIndex: 999999999,
				position: 'top', 
				resizable: true,
				modal:true,
				title: "Editar Macro", 
				open: function(type, data) {$("#PopupCargaZona").html(msg);$.unblockUI();}
			});
	   }
	});
}


function GuardarDatosColumna(plantmacrocolumnacod,zonacod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Modificando columna...</h1>',baseZ: 9999999999 })	
	param = $("#formcolumna").serialize();
	param += "&plantcod="+$("#plantcod").val(); 
	param += "&accion=2";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_confeccionar_carga_columnas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				CarcarColumnas(msg.macrozonacod);
				$("#PopupCargaZona").dialog('close');
				$.unblockUI();
			}else
			{
				alert(msg.Msg);	
				$.unblockUI();
			}
	   }
	});
}



