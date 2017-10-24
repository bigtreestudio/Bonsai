$(document).ready(function() {
	CrearBtUploadImg("#avatarImg","#size","#name","#file");

		$("#Eliminar").show();
//CrearBtUploadPdf("mul_multimedia_bt_subir_pdf","#mul_multimedia_previsualizar","#mul_multimedia_descripcion","#size","#name","#file","");
});

function CrearBtUploadImg(){
	 var argv = CrearBtUploadImg.arguments;
	 var idImgMostrar = argv[0];
	 var inputSize = argv[1];
	 var inputName = argv[2];
	 var inputFile = argv[3];
	  var uploader = new qq.FileUploader({
          element: document.getElementById("btn_subirImgMostrar"),
		  action: 'usuarios_multimedia_carga_temporal.php',
          multiple: false,
		  allowedExtensions: ['jpg', 'png', 'gif'],
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
						'<div class="qq-upload-button boton verde" style="height:36px !important;float:left;">Seleccione un avatar</div>' +
						'<ul class="qq-upload-list" style="float:left" ></ul>' + 
                        '<div class="qq-upload-size" style="float:left"></div>' +
                                            
                    '</div>', 
        onComplete: function(id, fileName, responseJSON) {
				 var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 $(idImgMostrar).html(htmlmostrar);
				 $(inputSize).val(responseJSON.size);
				 $(inputName).val(responseJSON.nombrearchivo);
				 $(inputFile).val(responseJSON.nombrearchivotmp);
				 $(".qq-upload-list").html("");
				 GuardarImg();
          },
		  messages: {
				typeError: "{file} extensión invalida. Se aceptan solo {extensions}.",
				sizeError: "{file}: el archivo es demasiado grande, solo se aceptan menores a {sizeLimit}.",
				minSizeError: "{file} archivo vacio.",
				emptyError: "{file} archivo vacio.",
				onLeave: "Los archivos se están cargando, si cerras ahora la carga se cancelará."            
		  },
          debug: false
      }); 
	  
}
function GuardarImg()
{
	param = $("#formulario").serialize();
	param += "&accion=1";
	$.blockUI({ message: '<h1 class="h1block"><img src="/bt-admin/images/cargando.gif" />Guardando...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "usuarios_modificar_avatar_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			$.unblockUI();
			$("#Eliminar").show();	
		}
		 else
		{
			alert(msg.Msg);	 
			$.unblockUI();	
		}
		 
	   }
	   
	 });
}

function EliminarFotoUsuario()
{
	var datos = $("#formulario").serialize();
	datos += "&accion=2";
	$.blockUI({ message: '<h1 class="h1block"><img src="/images/cargando.gif" />Eliminando avatar...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "usuarios_modificar_avatar_upd.php",
	   data: datos,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			$("#avatarImg").html('<img src="../multimedia/usuarios/avatar-l/default.png" title="Subir Avatar" style="border:1px solid; color:#999999;" />');
			$("#imagen").val("");
			$("#size").val("");
			$("#name").val("");
			$("#file").val("");
			$.unblockUI();	
			$("#Eliminar").hide();

		}
		 else
		{
			$.unblockUI();	
		}
		 
	   }
	   
	 });
}


