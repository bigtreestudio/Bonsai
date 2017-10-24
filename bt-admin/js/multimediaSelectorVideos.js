/*
Bigtree Studio
Class Multimedia.
*/


function SeleccionarMultimediaRepositorioVideos(campo)
{
		var param;
		$("#MsgGuardando").html("Cargando videos...")
		$("#MsgGuardando").show();
		param = "campoDevolucion="+campo
		$.ajax({
		   type: "POST",
		   url: "mul_multimedia_general_simple_videos.php",
		   data: param,
		   success: function(msg){
				$("#PopupVisualizarMultimedia").dialog({	
					height: 530, 
					width: 800, 
					zIndex: 999999999,
					position: 'center', 
					resizable: false,
					title: "Multimedia", 
					open: function(type, data) {$("#PopupVisualizarMultimedia").html(msg);},
					beforeclose: function(event, ui) {$("#PopupVisualizarMultimedia").html("");}
				});
				$("#MsgGuardando").hide();
				$("#MsgGuardando").html("Guardando...");
		   }
		 });
}


function EliminarMultimediaRepositorioVideos(campo)
{
	if(!confirm("Desea eliminar el video?"))
		return false;
	$("#"+campo).val("");
	$("#multimediapreview_"+campo).html("");
	$("#multimediaeliminar_"+campo).hide();
	return true;
}


function SeleccionarMultimediaSimpleVideos(codigo)
{
	PreviewMultimediaSimpleVideos(codigo);
	$("#PopupVisualizarMultimedia").dialog("close");
	$("#PopupVisualizarMultimedia").html("");
	$("#MsgGuardando").hide();
}


function PreviewMultimediaSimpleVideos(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediacod="+codigo; 
	var campodevolucion =$("#campodevolucion").val();
	var eliminar ='<a id="multimediaeliminar_'+campodevolucion+'"  href="javascript:void(0)" onclick="return EliminarMultimediaRepositorioVideos(\''+campodevolucion+'\')"><img src="images/cross.gif"  alt="Eliminar"></a>';
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_general_simple_visualizar.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			
			$("#"+campodevolucion).val(codigo);
			$("#multimediapreview_"+campodevolucion).html(msg.Multimedia+eliminar+msg.Titulo);
			$("#multimediapreview_"+campodevolucion).append('<div class="clearboth aire_vertical">&nbsp;</div>')
			$("#PopupVisualizarMultimedia").dialog("close");
			$("#PopupVisualizarMultimedia").html("");

	   }
	});
}

function SubirVideo()
{
	$("#MsgGuardando").html("Subiendo video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_video").serialize(); 
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
				PreviewMultimediaSimpleVideos(msg.multimediacod)
				$("#"+$("#campodevolucion").val()).val(msg.multimediacod);
				$("#PopupVisualizarMultimedia").dialog("close");
				$("#PopupVisualizarMultimedia").html("");

			}else
			{
				alert(msg.Msg);	
			}
			$.unblockUI();
			$("#MsgGuardando").hide();
	   }
	});
	
}

function SubirVideoPropietario()
{
	$("#MsgGuardando").html("Subiendo video");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_video_propietario").serialize(); 
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
				PreviewMultimediaSimpleVideos(msg.multimediacod)
				$("#"+$("#campodevolucion").val()).val(msg.multimediacod);
				$("#PopupVisualizarMultimedia").dialog("close");
				$("#PopupVisualizarMultimedia").html("");

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





