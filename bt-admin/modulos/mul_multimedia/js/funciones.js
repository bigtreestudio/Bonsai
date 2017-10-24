// Funciones Comunes de js

//Funcion que crea el boton para subir una imagen y crea un archivo temporal.
//Debe existir un elemento 
function CrearBtUploadImg(){
	
	 var argv = CrearBtUploadImg.arguments;
	 var BotonId = argv[0];
	 var idImgMostrar = argv[1];
	 var divGuardar = argv[2];
	 var inputSize = argv[3];
	 var inputName = argv[4];
	 var inputFile = argv[5];
	
	  var uploader = new qq.FileUploader({
          element: document.getElementById(BotonId),
          action: 'mul_multimedia_carga_temporal.php',
          multiple: false,
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Drop files here to upload</span></div>' +
                        '<div class="qq-upload-button" style="height:17px;">Seleccione un archivo</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   

         onComplete: function(id, fileName, responseJSON) {
				 var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 $(idImgMostrar).html(htmlmostrar);
				 $(inputSize).val(responseJSON.size);
				 $(inputName).val(responseJSON.nombrearchivo);
				 $(inputFile).val(responseJSON.nombrearchivotmp);
				 $(divGuardar).show();
          },
          messages: {
          },
          debug: false
      });          
}


function ModificarDescripcionMultimedia(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediadesc="+$("#multimediadesc_"+codigo).val(); 
	param += "&multimediacod="+codigo;
	param += "&accion=1";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_descripcion_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{

			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



function ModificarTituloMultimedia(codigo)
{
	$("#MsgGuardando").show();
	param = "multimediatitulo="+$("#multimediatitulo_"+codigo).val(); 
	param += "&multimediacod="+codigo;
	param += "&accion=2";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_descripcion_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{

			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
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
				width: 550, 
				zIndex: 999999999,
				position: 'top', 
				modal:true,
				title: "Multimedia", 
				open: function(type, data) {
						$("#PopupVisualizarMultimedia").html(msg);
						$("#MsgGuardando").hide();
						$("#MsgGuardando").html("Guardando...");
					}
			});
	   }
	 });
}
