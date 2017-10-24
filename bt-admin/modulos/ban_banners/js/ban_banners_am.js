

jQuery(document).ready(function(){
	//CARGA INICIAL DE LOS CARACTERES DE LA CARGA DEL BANNER DE LA DESCRIPCION CORTA
    jQuery('#bannerdesc').charCount();
    updateCharCount('bannerdesc');
	
	//CARGA INICIAL DE LOS CARACTERES DE LA CARGA DEL BANNER DE LA DESCRIPCION LARGA
    jQuery('#bannerdesclarga').charCount();
    updateCharCount('bannerdesclarga');

	initTextEditors();
	
	if ($("#accion").val()==2)
	{
		CrearBtUploadBanner();
	}
	//CARGA INICIAL DE LOS BOTONES Y MUESTA LA IMAGEN DADA DE ALTA
}); 


//FUNCION QUE LLAMA AL UPD E INSERTA TODOS LOS DATOS TRAIDOS DEL FORMULARIO

function InsertarBanner()
{
		$("#MsgGuardando").show();
		$(".msgaccionbanner").html("&nbsp;");
		var param, url;
		param = $("#formulario").serialize();
		$.ajax({
		   type: "POST",
		   url: "ban_banners_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
				if (msg.IsSuccess==true)
				{
					$(".msgaccionbanner").html(msg.Msg);
					document.location.href="ban_banners_am.php?bannercod="+msg.bannercod+"&md5="+msg.md5recarga;
					$.unblockUI();
				}else
				{
					alert(msg.Msg);
					$.unblockUI();
				}
				$("#MsgGuardando").hide();
	
		   }
		});		
		return true;
		 
}	


//FUNCION QUE LLAMA AL UPD Y MODIFICA TODOS LOS DATOS TRAIDOS DEL FORMULARIO

function ActualizarBanner()
{
	 var bannerdesclarga = tinyMCE.get('bannerdesclarga');
	$("#bannerdesclarga").val(bannerdesclarga.getContent());
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formulario").serialize();
	$(".msgaccionbanner").html("&nbsp;");
	$.ajax({
	   type: "POST",
	   url: "ban_banners_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){			
			if (msg.IsSuccess==true)
			{
				$(".msgaccionbanner").html(msg.Msg);
				CrearBtUploadBanner();
			}else
			{
				alert(msg.Msg);
			}
			$("#MsgGuardando").hide();
	   }
	});		
	return true;
}

//FUNCION QUE LLAMA AL UPD Y ELIMINA EL BANNER
//DATOS DE ENTRADA:
//      ACCION= ACCION QUE REALIZA (BAJA)
//      BANNERCOD= CODIGO DEL BANNER A ELIMINAR


function Eliminar()
{
	if (!confirm("Esta seguro que desea eliminar el banner?"))
		return false;
		
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando banner...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	$(".msgaccionbanner").html("&nbsp;");
	var param, url;
	param='accion=3&bannercod='+$("#bannercod").val();
	$.ajax({
	   type: "POST",
	   url: "ban_banners_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				$.unblockUI();
				document.location.href="ban_banners.php";
			}else
			{
				alert(msg.Msg);
				$.unblockUI();
			}
			$("#MsgGuardando").hide();
	   }
	});		
	return true;
}	



//Funcion que crea el boton para subir una imagen y crea un archivo temporal.
//Debe existir un elemento 
function CrearBtUploadBanner(){
	
	  var uploader = new qq.FileUploader({
          element: document.getElementById("btn_subirImgMostrar"),
          action: 'ban_banners_subir_archivo_upd.php',
          multiple: false,
		  allowedExtensions: ['jpg', 'png', 'gif', 'swf'],
		  params: {bannercod:$("#bannercod").val(), accion:1},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
						'<div class="qq-upload-button boton verde" style="height:17px;">Seleccione un archivo</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   
         onComplete: function(id, fileName, responseJSON) {
				 CargarBanner();
				 $(".qq-upload-list").html("");
          }
      });          
}



function CargarBanner()
{
	var param, url;
	$("#visualizarbanner").html("");
	param = "bannercod="+$("#bannercod").val();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando banner...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "ban_banners_am_archivo.php?rand="+Math.random(),
	   data: param,
	   cache: false,
	   dataType:"html",
	   success: function(msg){
			$("#visualizarbanner").html(msg);
			$.unblockUI();
	   }
	});		
	return true;
		 
}	

