/*
Bigtree Studio
Class Multimedia.
*/


function SeleccionarMultimediaRepositorioFotos(campo)
{
		var param;
		$("#MsgGuardando").html("Cargando fotos...")
		$("#MsgGuardando").show();
		param = "campoDevolucion="+campo
		$.ajax({
		   type: "POST",
		   url: "mul_multimedia_general_simple_fotos.php",
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


function EliminarMultimediaRepositorioFotos(campo)
{
	if(!confirm("Desea eliminar la imagen?"))
		return false;
	$("#"+campo).val("");
	$("#multimediapreview_"+campo).html("");
	$("#multimediaeliminar_"+campo).hide();
	return true;
}


function SeleccionarMultimediaSimpleFotos(codigo)
{
	PreviewMultimediaSimpleFotos(codigo);
	$("#PopupVisualizarMultimedia").dialog("close");
	$("#PopupVisualizarMultimedia").html("");

	$("#MsgGuardando").hide();
}



function SubirImagen()
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
				PreviewMultimediaSimpleFotos(msg.multimediacod)
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



function PreviewMultimediaSimpleFotos(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediacod="+codigo; 
	var campodevolucion =$("#campodevolucion").val();
	var eliminar ='<a id="multimediaeliminar_'+campodevolucion+'"  href="javascript:void(0)" onclick="return EliminarMultimediaRepositorioFotos(\''+campodevolucion+'\')"><img src="images/cross.gif"  alt="Eliminar"></a>';
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





