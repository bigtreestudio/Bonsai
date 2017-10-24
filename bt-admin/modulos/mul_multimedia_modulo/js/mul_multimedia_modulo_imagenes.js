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




