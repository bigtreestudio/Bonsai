// JavaScript Document
$(document).ready(function() {
	CrearBtUploadImg();
});

function CrearBtUploadImg(){
	  var uploader = new qq.FileUploader({
          element: document.getElementById("btn_subirImgMostrar"),
		  action: 'fon_fondos_multimedia_carga_temporal.php',
          multiple: false,
		  allowedExtensions: ['jpg', 'png', 'gif'],
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
						'<div class="qq-upload-button btn btn-info" style="float:left;height:20px;" onclick="if(!Validar())return false;" >Subir Imagen</div>' +
						'<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list" style="display:none;"></ul>' +                     
                    '</div>', 
					failUploadText: 'File Already Exist.',
        onComplete: function(id, fileName, responseJSON) {
				 var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 $("#ImgFondo").html(htmlmostrar);
				 $("#useravatar").html(htmlmostrar);
				 $("#size").val(responseJSON.size);
				 $("#name").val(responseJSON.nombrearchivo);
				 $("#file").val(responseJSON.nombrearchivotmp);
				 GuardarFotoLink();
				 
          },
		  onError: function (id, fileName, xhr) { alert(1)},
		  messages: {
				typeError: "{file} extensión invalida. Se aceptan solo {extensions}.",
				sizeError: "{file}: el archivo es demasiado grande, solo se aceptan menores a {sizeLimit}.",
				minSizeError: "{file} archivo vacio.",
				emptyError: "{file} archivo vacio.",
				onLeave: "Los archivos se están cargando, si cerras ahora la carga se cancelará."            
		  },
          debug: true
      }); 
	 	 
}

function GuardarFotoLink()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="./images/cargando.gif" />Guardando Fondo...</h1>',baseZ: 9999999999 })	
	var datos = $("#formalta").serialize();
	datos += "&accion=4";
	$.ajax({
	   type: "POST",
	   url: "fon_fondos_upd.php",
	   data: datos,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSucceed==true)
		{			
			$("#imagen").val("");
			$("#size").val("");
			$("#name").val("");
			$("#file").val("");
			$(".msj").html(msg.Msg);
			$(".msj").show();
			$(".MsgSuccess").show();
			$.unblockUI();
		}
		 else
		{
			alert(msg.Msg);	
			$.unblockUI();
		}
		 
	   }
	   
	 });
}

function Validar()
{
	if($("#fondocod").val()=="")
	{
		alert("Debe guardar antes de subir la imagen")
		return false;	
	}
			
	return true;		
}


function Publicar()
{
	if (!confirm("Esta seguro que desea publicar los fondos?"))
		return false;
		
	$.blockUI({ message: '<h1 class="h1block"><img src="./images/cargando.gif" />Publicando Fondos...</h1>',baseZ: 9999999999 })	
	datos = "accion=5";
	$.ajax({
	   type: "POST",
	   url: "fon_fondos_upd.php",
	   data: datos,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSucceed==true)
		{			
			$(".msj").html(msg.Msg);
			$(".msj").show();
			$(".MsgSuccess").show();
			$.unblockUI();
		}
		 else
		{
			alert(msg.Msg);	
			$.unblockUI();
		}
		 
	   }
	   
	 });
}
