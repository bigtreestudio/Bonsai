function AbrirPopupNuevaFoto()
{
	AbrirPopupMultimedia("Cargando im\u00e1genes...","gal_galerias_multimedia_foto.php");
	return true;
}

function AbrirPopupNuevoVideo()
{
	AbrirPopupMultimedia("Cargando im\u00e1genes...","gal_galerias_multimedia_video.php");
	return true;
}

function AbrirPopupNuevoAudio()
{
	AbrirPopupMultimedia("Cargando im\u00e1genes...","gal_galerias_multimedia_audio.php");
	return true;
}

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

function SeleccionarImagenMultimedia(multimediacod)
{
	
	$("#MsgGuardando").html("Seleccionado im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando im\u00e1gen...</h1>',baseZ: 9999999999 })	
	param = "galeriacod="+$("#galeriacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=6";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CargarListado();
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

function SeleccionarVideoMultimedia(multimediacod)
{
	$("#MsgGuardando").html("Seleccionado video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando video...</h1>',baseZ: 9999999999 })	
	param = "galeriacod="+$("#galeriacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=7";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 

			if (msg.success)
			{
				CargarListado();
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

function SeleccionarAudioMultimedia(multimediacod)
{
	$("#MsgGuardando").html("Seleccionado audio");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando audio...</h1>',baseZ: 9999999999 })	
	param = "galeriacod="+$("#galeriacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=8";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CargarListado();
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



function CargarListado(){
	
	var param, url;
	$("#MsgGuardando").html("Cargando archivos multimedia...")
	$("#MsgGuardando").show();

	param = "galeriacod="+$("#galeriacod").val();
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_lst.php",
	   data: param,
	   success: function(msg){
			$("#ListadoMultimedia").html(msg);
			CargarSortTable();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	 });

	return true;
		
}

function CargarSortTable()
{
	$(function() {
		$("#galeria_multimedia").sortable(
		  { 
			tolerance: 'pointer',
			scroll: true , 
			handle: $(".orden"),
			connectWith: '.galeria_multimedia_item',
			cursor: 'pointer',
			opacity: 0.6, 
			update: function() {
				var order = $(this).sortable("serialize")+"&galeriacod="+$("#galeriacod").val()+"&accion=5"; 
				$("#MsgGuardando").show();
				$.get("gal_galerias_multimedia_upd.php", order, function(msg){
					if (msg.success)
					{
						
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



function MostrarCaja(box){
	$(".boxsubidaData").hide();
	$("#"+box).show();
}





function ModificarDescripcionMultimedia(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediadesc="+$("#multimediadesc_"+codigo).val(); 
	param += "&codigo="+$("#galeriacod").val();
	param += "&multimediacod="+codigo;
	param += "&accion=10";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{

			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



function ModificarTituloMultimedia(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediatitulo="+$("#multimediatitulo_"+codigo).val(); 
	param += "&codigo="+$("#galeriacod").val();
	param += "&multimediacod="+codigo;
	param += "&accion=9";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{

			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function VisualizarMultimedia(codigo)
{
	$("#MsgGuardando").html("Cargando archivo multimedia...");
	$("#MsgGuardando").show();
	param = "multimediacod="+codigo;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_visualizar.php",
	   data: param,
	   success: function(msg){
			$("#PopupVisualizarMultimedia").dialog({	
				width: 550, 
				zIndex: 999999999,
				position: 'top', 
				modal:true,
				title: "Multimedia", 
				open: function(type, data) {
						$("#PopupVisualizarMultimedia").html(msg);
						$("#MsgGuardando").hide();
						$("#MsgGuardando").html("Guardando...");
					}
			});
	   }
	 });
}



function CheckearTodos()
{
	if ($("#todos").prop("checked")==true)
		$(".multcheck").prop("checked",true);
	else
		$(".multcheck").prop("checked",false);
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
	param = "galeriacod="+$("#galeriacod").val();
	param += "&multimedia="+arregloChk;
	param += "&accion=13";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CargarListado();
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



var MultimediaRelacionar = "";
function BuscarPreview(multimediacod)
{
	var param, url;
	$("#MsgGuardando").html("Cargando formulario...")
	$("#MsgGuardando").show();
	param = "multimediacod="+multimediacod; 
	MultimediaRelacionar=multimediacod;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_general_preview_fotos_am.php",
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



function RelacionarPreview(multimediacod)
{
	var param, url;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Relacionando preview...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	param = "multimediacod="+MultimediaRelacionar; 
	param += "&multimediacodRelacion="+multimediacod; 
	param += "&codigo="+$("#galeriacod").val(); 
	param += "&accion=12"; 
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CargarListado();
				DialogClose();
			}else
			{
				alert(msg.Msg);	
			}
			 $.unblockUI();
			$("#MsgGuardando").hide();
	   }
	});

	return true;
}

function EliminarPreview(multimediacod)
{
	if (!confirm("Esta seguro que desea eliminar el preview?"))
		return false;
		
	var param, url;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando preview...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	param = "multimediacod="+multimediacod; 
	param += "&codigo="+$("#galeriacod").val(); 
	param += "&accion=11"; 
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CargarListado();
			}else
			{
				alert(msg.Msg);	
			}
			 $.unblockUI();
			$("#MsgGuardando").hide();
	   }
	});

	return true;
}


function GuardarImagenPreview()
{
	$("#MsgGuardando").show();
	var param, url;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo preview...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_img").serialize(); 
	param += "&multimediacod="+MultimediaRelacionar;
	param += "&codigo="+$("#galeriacod").val();
	param += "&accion=14";
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CargarListado();
				DialogClose();
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

