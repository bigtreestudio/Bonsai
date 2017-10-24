

jQuery(document).ready(function(){
	//CARGA INICIAL DE LOS CARACTERES DE LA CARGA DEL TOP DE LA DESCRIPCION CORTA
    jQuery('#topdesc').charCount();
    updateCharCount('topdesc');
	
	//CARGA INICIAL DE LOS CARACTERES DE LA CARGA DEL TOP DE LA DESCRIPCION LARGA
    jQuery('#topdesclarga').charCount();
    updateCharCount('topdesclarga');

	initTextEditors();
	
	if ($("#accion").val()==2)
	{
		CrearBtUploadTop();
	}
	//CARGA INICIAL DE LOS BOTONES Y MUESTA LA IMAGEN DADA DE ALTA
}); 


//FUNCION QUE LLAMA AL UPD E INSERTA TODOS LOS DATOS TRAIDOS DEL FORMULARIO

function Insertartop()
{
		$("#MsgGuardando").show();
		$(".msgacciontop").html(" ");
		var param, url;
		param = $("#formulario").serialize();
		$.ajax({
		   type: "POST",
		   url: "top_top_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
				if (msg.IsSuccess==true)
				{
					$(".msgacciontop").html(msg.Msg);
					document.location.href="top_top_am.php?topcod="+msg.topcod+"&md5="+msg.md5recarga;
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

function ActualizarTop()
{
	 var topdesclarga = tinyMCE.get('topdesclarga');
	$("#topdesclarga").val(topdesclarga.getContent());
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formulario").serialize();
	$(".msgacciontop").html("");
	$.ajax({
	   type: "POST",
	   url: "top_top_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){			
			if (msg.IsSuccess==true)
			{
				$(".msgacciontop").html(msg.Msg);
				CrearBtUploadTop();
			}else
			{
				alert(msg.Msg);
			}
			$("#MsgGuardando").hide();
	   }
	});		
	return true;
}

//FUNCION QUE LLAMA AL UPD Y ELIMINA EL TOP
//DATOS DE ENTRADA:
//      ACCION= ACCION QUE REALIZA (BAJA)
//      TOPCOD= CODIGO DEL TOP A ELIMINAR


function Eliminar()
{
	if (!confirm("Esta seguro que desea eliminar el top?"))
		return false;
		
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando top...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	$(".msgacciontop").html(" ");
	var param, url;
	param='accion=3&topcod='+$("#topcod").val();
	$.ajax({
	   type: "POST",
	   url: "top_top_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				$.unblockUI();
				document.location.href="top_top.php";
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
function CrearBtUploadTop(){
	
	  var uploader = new qq.FileUploader({
          element: document.getElementById("btn_subirImgMostrar"),
          action: 'top_top_subir_archivo_upd.php',
          multiple: false,
		  allowedExtensions: ['jpg', 'png', 'gif', 'swf'],
		  params: {topcod:$("#topcod").val(), accion:1},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aquí</span></div>' +
						'<div class="qq-upload-button boton azul" style="height:17px;">Seleccione un archivo</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   
         onComplete: function(id, fileName, responseJSON) {
				 CargarTop();
				 $(".qq-upload-list").html("");
          }
      });          
}



function CargarTop()
{
	var param, url;
	$("#visualizartop").html("");
	param = "topcod="+$("#topcod").val();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando top...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "top_top_am_archivo.php?rand="+Math.random(),
	   data: param,
	   cache: false,
	   dataType:"html",
	   success: function(msg){
			$("#visualizartop").html(msg);
			$.unblockUI();
	   }
	});		
	return true;
		 
}	

