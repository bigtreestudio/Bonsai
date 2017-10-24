jQuery(document).ready(function(){
	
});



function Buscar(){
	var param, url;
	$("#MsgGuardando").html("Buscando.")
	$("#MsgGuardando").show();
	param=$("#busquedamultimedia").serialize();
	url = archivoreload;
	$.ajax({
	   type: "POST",
	   data: param,
	   url: url,
	   success: function(msg){
			$("#ListadoMultimedia").html(msg);
			$("#MsgGuardando").hide();
	   }
	 });

	return true;
}


function CargarListado(pagina){
	var param, url;
	$("#MsgGuardando").html("Cargando archivos multimedia...")
	$("#MsgGuardando").show();
	url = archivoreload+"?pagina="+pagina;
	
	$.ajax({
	   type: "POST",
	   url: url,
	   data: param,
	   success: function(msg){
			$("#ListadoMultimedia").html(msg);
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	 });

	return true;
		
}



function AbrirPopupNuevoAudioModuloAudios()
{
	AbrirPopupMultimediaModulo("Cargando audios...","mul_multimedia_audio_am.php");
	return true;
}


function AbrirPopupNuevaFotoModuloImagenes()
{
	AbrirPopupMultimediaModulo("Cargando im\u00e1genes...","mul_multimedia_foto_am.php");
	return true;
}

function AbrirPopupNuevoArchivoModuloArchivos()
{
	AbrirPopupMultimediaModulo("Cargando archivos...","mul_multimedia_archivo_am.php");
	return true;
}

function AbrirPopupNuevoVideoModuloVideos()
{
	AbrirPopupMultimediaModulo("Cargando audios...","mul_multimedia_video_am.php");
	return true;
}


function AbrirPopupMultimediaModulo(titulo,archivo)
{
	var param, url;
	$("#MsgGuardando").html(titulo)
	$("#MsgGuardando").show();
	$("#PopupMultimedia").html("");
	$.ajax({
	   type: "POST",
	   url: archivo,
	   data: param,
	   success: function(msg){
			$("#PopupMultimediaAlta").dialog({	
				width: 800, 
				height: 550,
				zIndex: 999999999,
				position: 'center', 
				resizable: false,
				title: "Multimedia", 
				open: function(type, data) {$("#PopupMultimediaAlta").html(msg);}
			});
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	 });

	return true;
}

function DialogClose()
{
	 $("#PopupMultimedia").dialog("close"); 
}

function DialogCloseAlta()
{
	 $("#PopupMultimediaAlta").dialog("close"); 
}

function LimpiarFiltrosImagenes()
{
	$("#multimedianombre").val("");
	$("#multimediatitulo").val("");
	$("#multimediatipoarchivo").val("");
	$("#multimediaestadocod").val("");

}


function LimpiarFiltrosImagenes()
{
	$("#multimedianombre").val("");
	$("#multimediatitulo").val("");
	$("#multimediatipoarchivo").val("");
	$("#multimediaestadocod").val("");

}

function CheckearTodos()
{
	if ($("#todos").prop("checked")==true){
		$(".multcheck").prop("checked",true);
		$(".caja_imagen").addClass("activarCaja");

	}else{
		$(".multcheck").prop("checked",false);
		$(".caja_imagen").removeClass("activarCaja");

	}
}

function Seleccionado(codigo)
{
	if ($("#multcheck_"+codigo).prop("checked")==true){
		$("#imagen_"+codigo).addClass("activarCaja");
	}else{
		$("#imagen_"+codigo).removeClass("activarCaja");
	}
}

function EliminarMultimediaModuloCompleto(multimediacod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el multimedia"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando multimedia...</h1>',baseZ: 99999999999});
	param = "multimediacod="+multimediacod;
	param += "&accion=1";
	EnviarDatosEliminar(param,multimediacod);

	return true;
}

function EnviarDatosEliminar(param,codigo)
{
	$.ajax({
		type: "POST",
		url: "mul_multimedia_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSuccess==true)
			{
				alert(msg.Msg);
				CargarListado(1);
				$.unblockUI(); 

			}
			else
			{
				alert(msg.Msg); 
				$.unblockUI(); 
			}
		}
	});
} 


function EliminarCheckeados()
{
	var arregloChk=new Array();
	$(".multcheck").each(function(){
		if ($(this).prop("checked")==true)
			arregloChk.push($(this).val());			
	})
	
	if (arregloChk.length<1)
	{
		alert("Debe seleccionar al menos un elemento.");
		return false;	
	}
	if(!confirm("Esta seguro que desea eliminar todos lo chequeados?"))	
		return true;
	
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando multimedias seleccionados...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").html("Eliminando multimedias seleccionados...");
	$("#MsgGuardando").show();
	param += "&multimedia="+arregloChk;
	param += "&accion=4";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess==true)
			{
				 CargarListado(1);
			}else
			{
				alert(msg.Msg);	
			}
			 $.unblockUI();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
	
}
function ModificarDescripcionMultimediaUnicoCompleto(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediadesc="+$("#multimedia_desc").val(); 
	param += "&multimediatitulo="+$("#multimedia_titulo").val(); 
	param += "&multimediacod="+codigo;
	param += "&catcod="+$("#form_mul_multimedia #catcod").val();
	
	param += "&accion=3";

	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_descripcion_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{	
				$("#PopupMultimedia").dialog("close"); 
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}


function EditarMultimediaModulo(multimediacod,tipo)
{
	switch(tipo)
	{
		case 1:
		 	 archivo ="mul_multimedia_foto_am.php";
		  break;
		case 2:
		 	 archivo="mul_multimedia_video_am.php";
		  break;
		case 3:
			archivo="mul_multimedia_audio_am.php";
		  break;
		case 4:
			archivo="mul_multimedia_archivo_am.php";
		  break;  
		default:
		  break;
	}
	
	AbrirPopupMultimediaModuloEditar(multimediacod,archivo);
	
	return true;
} 

function AbrirPopupMultimediaModuloEditar(multimediacod,archivo)
{
	var param, url;

	param="";
	param += "&multimediacod="+multimediacod;
	param += "&modif=true";
	$("#MsgGuardando").show();
	$.ajax({
	   type: "POST",
	   url: archivo,
	   data: param,
	   success: function(msg){
			$("#PopupMultimedia").dialog({	
				height: 370, 
				width: 800, 
				zIndex: 999999999,
				position: 'center', 
				resizable: false,
				title: "Multimedia", 
				open: function(type, data) {$("#PopupMultimedia").html(msg);}
			});
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	 });

	return true;
}



function ModificarDescripcionMultimediaModuloUnico(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediadesc="+$("#multimediadesc_"+codigo).val(); 
	param += "&multimediacod="+codigo;
	param += "&catcod="+$("#form_mul_multimedia_img #catcod").val();
	
	param += "&accion=1";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_descripcion_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$("#PopupMultimedia").dialog("close"); 

			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
}




function AgregarPreview(multimediacod)
{
	$("#MsgGuardando").html("Subir Preview...");
	$("#MsgGuardando").show();
	param = "multimediacod="+multimediacod;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_general_preview_fotos_am.php",
	   data: param,
	   success: function(msg){
			$("#PopupMultimedia").dialog({	
				width: 800, 
				height: 480, 
				zIndex: 999999999,
				position: 'top', 
				modal:true,
				title: "Preview", 
				open: function(type, data) {
						$("#PopupMultimedia").html(msg);
						$("#MsgGuardando").hide();
						$("#MsgGuardando").html("Guardando...");
					},
				close: function(type, data) {
						$("#PopupMultimedia").html("");
					}
			});
	   }
	 });

	}
	

function GuardarImagenPreview()
{
	$("#MsgGuardando").show();
	var param, url;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo preview...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_img").serialize(); 
	param += "&multimediacod="+$("#multimediacod").val();
	param += "&accion=1";
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_video_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				CargarListado(1);
				DialogClose();
				$("#PopupMultimedia").html("");
			}else
			{
				alert(msg.Msg);	
			}
			$.unblockUI();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});

}

function RelacionarPreview(multimediapreview)
{
	var param;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Asociando multimedia...</h1>',baseZ: 99999999999});
	param = $("#form_mul_multimedia_img").serialize(); 
	param += "&multimediapreview="+multimediapreview;
	param += "&accion=3";
	$.ajax({
		type: "POST",
		url: "mul_multimedia_video_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				alert(msg.Msg);
				CargarListado(1);
				DialogClose();
				$("#PopupMultimedia").html("");
				$.unblockUI(); 
			}
			else
			{
				alert(msg.Msg); 
				$.unblockUI(); 
			}

		}
	});
	
	return true;
}




function EliminarPreview(multimediacod,multimediapreview,archivomultimedia)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea desasociar el preview?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Desasociando multimedia...</h1>',baseZ: 99999999999});
	param = "multimediacod="+multimediacod;
	param += "&multimediapreview="+multimediapreview;
	param += "&accion=2";
	EnviarDatosPreview(param,multimediacod,archivomultimedia);

	return true;
}

function EnviarDatosPreview(param,codigo,archivomultimedia)
{
	$.ajax({
		type: "POST",
		url: "mul_multimedia_video_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				CargarListado(1);
				$.unblockUI(); 
				$("#PopupMultimedia").html("");
			}
			else
			{
				alert(msg.Msg); 
				$.unblockUI(); 
			}
		}
	});
}
