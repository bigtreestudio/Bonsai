/*
Bigtree Studio
Class Multimedia.
*/


function SeleccionarMultimediaRepositorioAudios(campo)
{
		var param;
		$("#MsgGuardando").html("Cargando audios...")
		$("#MsgGuardando").show();
		param = "campoDevolucion="+campo
		$.ajax({
		   type: "POST",
		   url: "mul_multimedia_general_simple_audios.php",
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

function EliminarMultimediaRepositorioAudios(campo)
{
	if(!confirm("Desea eliminar el audio?"))
		return false;
	$("#"+campo).val("");
	$("#multimediapreview_"+campo).html("");
	$("#multimediaeliminar_"+campo).hide();
	return true;
}


function SeleccionarMultimediaSimpleAudios(codigo)
{
	PreviewMultimediaSimpleAudios(codigo);
	$("#PopupVisualizarMultimedia").dialog("close");
	$("#PopupVisualizarMultimedia").html("");
	$("#MsgGuardando").hide();
}


function SubirAudio()
{
	//$("#MsgGuardando").html("Subiendo im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo audio...</h1>',baseZ: 9999999999 })	
	param = $("#form_mul_multimedia_audios").serialize(); 
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
				PreviewMultimediaSimpleAudios(msg.multimediacod)
				$("#"+$("#campodevolucion").val()).val(msg.multimediacod);
				$("#PopupVisualizarMultimedia").dialog("close");
			}else
			{
				alert(msg.Msg);	
			}
			$.unblockUI();
			$("#MsgGuardando").hide();
	   }
	});
	
}

function PreviewMultimediaSimpleAudios(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediacod="+codigo; 
	var campodevolucion =$("#campodevolucion").val();
	var eliminar ='<a id="multimediaeliminar_'+campodevolucion+'"  href="javascript:void(0)" onclick="return EliminarMultimediaRepositorioAudios(\''+campodevolucion+'\')"><img src="images/cross.gif"  alt="Eliminar"></a>';
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





