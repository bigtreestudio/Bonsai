// JavaScript Document

//Abre un ventana modal en la cual trae los datos para subir una nueva imagen o seleccionar una existente.
//Se debe definir un id PopupMultimedia en el código html para poder abrir el popup.
function AbrirPopupNuevaFoto()
{
	AbrirPopupMultimedia("Cargando im\u00e1genes...","mul_multimedia_foto.php");
	return true;
}

//Abre un ventana modal en la cual trae los datos para subir un nuevo video o seleccionar uno existente.
//Se debe definir un id PopupMultimedia en el código html para poder abrir el popup.
function AbrirPopupNuevoVideo()
{
	AbrirPopupMultimedia("Cargando videos...","mul_multimedia_video.php");
	return true;
}

//Abre un ventana modal en la cual trae los datos para subir un nuevo audio seleccionar uno existente.
//Se debe definir un id PopupMultimedia en el código html para poder abrir el popup.
function AbrirPopupNuevoAudio()
{
	AbrirPopupMultimedia("Cargando audios...","mul_multimedia_audio.php");
	return true;
}


//Abre un ventana modal en la cual trae los datos para subir una nueva imagen o seleccionar una existente.
//Se debe definir un id PopupMultimedia en el código html para poder abrir el popup.
function AbrirPopupMultimedia(titulo,archivo)
{

	if ($("#agendacod").val()=="")
	{
		alert("Debe guardar el evento antes de subir un archivo multimedia");
		return false;			
	}

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

//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function GuardarImagen()
{
	//$("#MsgGuardando").html("Subiendo im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo im\u00e1gen...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_img").serialize(); 
	param += "&agendacod="+$("#agendacod").val(); 
	param += "&accion=1";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(1,"#fotos")
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


//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function SeleccionarImagenMultimedia(multimediacod)
{
	$("#MsgGuardando").html("Seleccionado im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando im\u00e1gen...</h1>',baseZ: 9999999999 })	
	param = "agendacod="+$("#agendacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=6";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(1,"#fotos")
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
			 $.unblockUI();
	   }
	});
	
}




//ELIMINAR LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function EliminarMultimedia(multimediacod,tipo,id)
{
	if (!confirm("Esta seguro que desea eliminar el archivo multimedia del evento?"))
		return false;
		
	$("#MsgGuardando").html("Eliminando...");
	$("#MsgGuardando").show();
	param = "&agendacod="+$("#agendacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=4";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				CargarMultimedia(tipo,id)
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
	
}




//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function CargarMultimedia(tipo,idCarga)
{
	var param, url;
	$("#MsgGuardando").html("Cargando imagenes...");
	$("#MsgGuardando").show();
	param = "agendacod="+$("#agendacod").val(); 
	param += "&tipo="+tipo;

	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_lst.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			jQuery(idCarga).html(msg);
			CargarSortTable(tipo);
			jQuery("#MsgGuardando").hide();
			jQuery("#MsgGuardando").html("Guardando...");
	   }
	});
	
}


function CargarSortTable(tipo)
{
	var id;
	var connect;
	switch(tipo)
	{
		case 1:
			tipo = "#multimedia_fotos";
			connect = ".sortable_multimedia_fotos";
		break;	
		case 2:
			tipo = "#multimedia_videos";
			connect = ".sortable_multimedia_videos";
		break;	
		case 3:
			tipo = "#multimedia_audios";
			connect = ".sortable_multimedia_audios";
		break;	
		
	}
	$(".msgaccionpagina").html("&nbsp;");
		$(function() {
			$(tipo).sortable(
			  { 
				tolerance: 'pointer',
				scroll: true , 
				handle: $(".orden"),
				connectWith: connect,
				axis: 'y',
				cursor: 'pointer',
				opacity: 0.6, 
				update: function() {
					
					var order = $(this).sortable("serialize")+"&agendacod="+$("#agendacod").val()+"&accion=5"; 	
					$("#MsgGuardando").show();
					$.post("age_agenda_multimedia_upd.php", order, function(msg){
						if (msg.IsSucceed)
						{
							$(".msgaccionpagina").html("&nbsp;");
						}else
						{
							alert(msg.Msg)	
						}
						$("#MsgGuardando").hide();
					}, "json");
				}				  
			});
			
		});// fin functio()
	
}



/*VIDEOS*/

//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function GuardarVideo()
{
	$("#MsgGuardando").html("Subiendo video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_video").serialize(); 
	param += "&agendacod="+$("#agendacod").val(); 
	param += "&accion=2";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(2,"#videos")
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


//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function SeleccionarVideoMultimedia(multimediacod)
{
	$("#MsgGuardando").html("Seleccionado video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando video...</h1>',baseZ: 9999999999 })	
	param = "agendacod="+$("#agendacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=7";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(2,"#videos")
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
			 $.unblockUI();
	   }
	});
	
}

 
 


/*AUDIOS*/

//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function GuardarAudio()
{
	$("#MsgGuardando").html("Subiendo audio");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo audio...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_audio").serialize(); 
	param += "&agendacod="+$("#agendacod").val(); 
	param += "&accion=3";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(3,"#audios")
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


//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE PAGINA
function SeleccionarAudioMultimedia(multimediacod)
{
	$("#MsgGuardando").html("Seleccionado audio");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando audio...</h1>',baseZ: 9999999999 })	
	param = "agendacod="+$("#agendacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=8";
	
	$(".msgaccionpagina").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$(".msgaccionpagina").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(3,"#audios")
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
			 $.unblockUI();
	   }
	});
	
}

 
 
