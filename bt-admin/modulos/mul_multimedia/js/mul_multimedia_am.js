function AbrirPopupNuevoArchivo()
{
	AbrirPopupMultimedia("Cargando archivos...","mul_multimedia_archivo_am.php");
	return true; 
}

function AbrirPopupNuevaFoto()
{
	AbrirPopupMultimedia("Cargando im\u00e1genes...","mul_multimedia_foto_am.php");
	return true;
}

function AbrirPopupNuevoAudio()
{
	AbrirPopupMultimedia("Cargando audios...","mul_multimedia_audio_am.php");
	return true;
}

function AbrirPopupNuevoVideo()
{
	AbrirPopupMultimedia("Cargando videos...","mul_multimedia_video_am.php");
	return true;
}


//Abre un ventana modal en la cual trae los datos para subir una nueva imagen o seleccionar una existente.
//Se debe definir un id PopupMultimedia en el código html para poder abrir el popup.
function AbrirPopupMultimedia(titulo,archivo)
{
	var param, url;
	$("#MsgGuardando").html(titulo)
	$("#MsgGuardando").show();
	$.ajax({
	   type: "POST",
	   url: archivo,
	   data: param,
	   success: function(msg){
			$("#PopupMultimedia").dialog({	
				height: 500, 
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


function DialogClose()
{
	 $("#PopupMultimedia").dialog("close"); 
}


function GuardarImagen()
{
	//$("#MsgGuardando").html("Subiendo im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo im\u00e1gen...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_img").serialize(); 
	param += "&accion=1";
	
		var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_am_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 

			if (msg.IsSucceed)
			{
				$("#PopupMultimedia").dialog("close"); 
				gridReload();
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

function GuardarVideo()
{
	$("#MsgGuardando").html("Subiendo video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#multimediaformulario").serialize(); 
	param += "&accion=2";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_am_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$("#PopupMultimedia").dialog("close"); 
				gridReload();
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

function GuardarVideoPropietario()
{
	$("#MsgGuardando").html("Subiendo video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#multimediaformvidpropietario").serialize(); 
	param += "&accion=2";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_am_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$("#PopupMultimedia").dialog("close"); 
				gridReload();
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

function GuardarAudio()
{
	$("#MsgGuardando").html("Subiendo audio");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo audio...</h1>',baseZ: 9999999999 })	
	param = $("#multimediaformulario").serialize(); 
	param += "&accion=3";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_am_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 

			if (msg.IsSucceed)
			{
				$("#PopupMultimedia").dialog("close"); 
				gridReload();
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


function GuardarArchivo()
{
	$("#MsgGuardando").html("Subiendo archivo");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo archivo...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_file").serialize(); 
	param += "&accion=4";
		var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_am_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 

			if (msg.IsSucceed)
			{
				$("#PopupMultimedia").dialog("close"); 
				gridReload();
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

function EditarMultimedia(multimediacod,tipo)
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
	
	AbrirPopupMultimediaEditar(multimediacod,archivo);
	
	return true;
}

function AbrirPopupMultimediaEditar(multimediacod,archivo)
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
				height: 250, 
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

function ModificarDescripcionMultimediaUnico(codigo)
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
				gridReload();
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function ModificarDescripcionMultimediaUnico2(codigo)
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
				gridReload();
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



