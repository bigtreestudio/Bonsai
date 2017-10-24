jQuery(document).ready(function(){
	  $('a.zoom').fullsizable({
        detach_id: 'container',
        clickBehaviour: 'next'
      });  
    
      $(document).on('fullsizable:opened', function(){
        $("#jquery-fullsizable").swipe({
          swipeLeft: function(){
            $(document).trigger('fullsizable:next')
          },
          swipeRight: function(){
            $(document).trigger('fullsizable:prev')
          },
          swipeUp: function(){
            $(document).trigger('fullsizable:close')
          }
        });
      });
});




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

function DialogClose()
{
	 $("#PopupMultimedia").dialog("close"); 
}

function DialogCloseAlta()
{
	 $("#PopupMultimediaAlta").dialog("close"); 
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
