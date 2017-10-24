// JavaScript Document
jQuery(document).ready(function(){	
	CrearBtUploadRevTapaMultiple("mul_multimedia_bt_subir_tapa","#mul_multimedia_previsualizar_tapa","#mul_multimedia_descripcion","#size","#name","#file","");

});



function CrearBtUploadRevTapaMultiple(){
	 
	 
	  var uploader = new qq.FileUploader({
          element: document.getElementById("mul_multimedia_bt_subir_tapa"),
          action: "rev_tapas_tapa_multimedia_upd.php",
		  method: "POST",
          multiple: true,
		  //sizeLimit: sizeLimitFile, // max size
		  allowedExtensions: ['jpg', 'png', 'gif'],
		  params: {revtapacod:$("#revtapacod").val(),accion:1},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
                        '<div class="qq-upload-button" style="height:17px;">Seleccionar y subir pagina/s</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   
		 onComplete: function(id, fileName, responseJSON) {
				 //var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 if (responseJSON.success==true)
				 	gridReload();

          },
          messages: {
          },
		  showMessage: function(message){ alert(message); },
          debug: false
      });                
}
function gridReload(){ 
	var datos = $("#formrevtapamultimedia").serializeObject();
	jQuery("#ListarRevTapaMultimedia").jqGrid('setGridParam', {url:"rev_tapas_tapa_multimedia_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


function DialogClose()
{
	 $("#Popup").dialog("close"); 
}


function GuardarRevTapaMultimedia()
{
	//$("#MsgGuardando").html("Subiendo im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo archivo...</h1>',baseZ: 9999999999 })	
	param = $("#formulariorevtapas").serialize(); 
	param += "&accion=1";
	
		var param, url;
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_tapa_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 

			if (msg.IsSuccess)
			{
				gridReload();
				$("#Popup").dialog("close"); 
				$.unblockUI();	
			}else
			{
				alert(msg.Msg);	
				$.unblockUI();	
			}
			$.unblockUI();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
	
}

function GuardarEditRevTapaMultimedia()
{
	//$("#MsgGuardando").html("Subiendo im\u00e1gen");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Guardando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulariorevtapas").serialize(); 
	param += "&accion=2";
	
		var param, url;
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_tapa_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 

			if (msg.IsSuccess)
			{
				gridReload();
				alert(msg.Msg);
				$("#Popup").dialog("close"); 
				$.unblockUI();	
			}else
			{
				alert(msg.Msg);	
				$.unblockUI();	
			}
			$.unblockUI();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
	
}








 
 
