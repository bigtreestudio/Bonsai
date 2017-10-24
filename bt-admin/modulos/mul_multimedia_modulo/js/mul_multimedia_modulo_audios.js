jQuery(document).ready(function(){
	
});


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
