jQuery(document).ready(function(){
});


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
				$("#PopupMultimediaAlta").dialog("close"); 
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

function VisualizarMultimediaModuloArchivos(codigo)
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
				width: 650, 
				zIndex: 999999999,
				position: 'top', 
				modal:true,
				title: "Multimedia", 
				open: function(type, data) {
						$("#PopupVisualizarMultimedia").html(msg);
						$("#MsgGuardando").hide();
						$("#MsgGuardando").html("Guardando...");
					},
				close: function(type, data) {
						$("#PopupVisualizarMultimedia").html("");
					}
			});
	   }
	 });

}





