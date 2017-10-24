// JavaScript Document
jQuery(document).ready(function(){	
	CargarListado();
	
	if ($("#multimediaconjuntocod").val()==1)
			CrearBtUploadImgMultiple();

	if ($("#multimediaconjuntocod").val()==2)
			CrearBtUploadVideoPropietario();

	if ($("#multimediaconjuntocod").val()==3)
			CrearBtUploadAudMultiple();	
	
});



function CargarListado(){
	var param, url;
	$("#MsgGuardando").html("Cargando archivos multimedia...")
	$("#MsgGuardando").show();

	param = "galeriacod="+$("#galeriacod").val();
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_lst.php",
	   data: param,
	   success: function(msg){
			$("#ListadoMultimedia").html(msg);
			CargarSortTable();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	 });

	return true;
		
}


function CrearBtUploadVideoPropietario(){


	  var uploader = new qq.FileUploader({
          element: document.getElementById("mul_multimedia_bt_subir_video_propietario"),
          action: 'mul_multimedia_carga_temporal.php',
		  method: "POST",
          multiple: false,
		  sizeLimit: sizeLimitFile, // max size
		  allowedExtensions: ['mp4','flv'],
		  params: {galeriacod:$("#galeriacod").val(), accion:15},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
                        '<div class="qq-upload-button boton verde" style="height:17px;">Seleccione un archivo</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   
		 onComplete: function(id, fileName, responseJSON) {
				 var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 $("#PreviewVideoPropietario").html(htmlmostrar);
				 $("#mul_multimedia_size").val(responseJSON.size);
				 $("#mul_multimedia_name").val(responseJSON.nombrearchivo);
				 $("#mul_multimedia_file").val(responseJSON.nombrearchivotmp);
				 $("#mul_multimedia_subir_prop").show();
          },
          messages: {
          },
		  showMessage: function(message){ alert(message); },
          debug: false
      });          
}

function CancelarVideoPropietario()
{
	 $("#PreviewVideoPropietario").html("");
	 $("#mul_multimedia_size").val("");
	 $("#mul_multimedia_name").val("");
	 $("#mul_multimedia_file").val("");
	 $("#mul_multimedia_subir_prop").hide();
}


function GuardarVideoPropietario()
{
	$("#MsgGuardando").html("Subiendo video...");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#formulariogaleria").serialize(); 
	param += "&galeriacod="+$("#galeriacod").val(); 
	param += "&accion=15";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				CancelarVideoPropietario();
				CargarListado();
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


function CrearBtUploadImgMultiple(){


	  var uploader = new qq.FileUploader({
          element: document.getElementById("mul_multimedia_bt_subir_img"),
          action: "gal_galerias_multimedia_upd.php",
		  method: "POST",
          multiple: true,
		  //sizeLimit: sizeLimitFile, // max size
		  allowedExtensions: ['jpg', 'png', 'gif'],
		  params: {galeriacod:$("#galeriacod").val(), accion:1},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
                        '<div class="qq-upload-button boton verde" style="height:17px;">Seleccione un archivo</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   
		 onComplete: function(id, fileName, responseJSON) {
				 //var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 if (responseJSON.success==true)
				 	CargarListado();
				 else
				 	alert(responseJSON.Msg);	
          },
          messages: {
          },
		  showMessage: function(message){ alert(message); },
          debug: false
      });          
}

function CrearBtUploadAudMultiple(){
	  var uploader = new qq.FileUploader({
          element: document.getElementById("mul_multimedia_bt_subir_audio"),
          action: "gal_galerias_multimedia_upd.php",
          multiple: true,
		  //sizeLimit: sizeLimitFileAudio, // max size
		  allowedExtensions: ['mp3'],
		  params: {galeriacod:$("#galeriacod").val(), accion:3},
		  template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
                        '<div class="qq-upload-button boton verde" style="height:17px;">Seleccione un archivo</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   

		 onComplete: function(id, fileName, responseJSON) {
				 if (responseJSON.success==true)
				 	CargarListado();
				 else
				 	alert(responseJSON.Msg);	
          },
          messages: {
          },
		   showMessage: function(message){ alert(message); },
          debug: false
      });          
}

//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE NOTICIA

function BuscarVideoExterno()
{
	$("#MsgGuardando").html("Buscando video...");
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formulariogaleria").serialize(); 
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_videos_externos_previsualizacion.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#mul_multimedia_previsualizar_am").html(msg);
			$("#mul_multimedia_subir_am").show();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
}


function CancelarVideo()
{
	$("#mul_multimedia_previsualizar_am").html("");
	$("#mul_multimedia_subir_am").hide();
}


function GuardarVideo()
{
	$("#MsgGuardando").html("Subiendo video...");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Subiendo video...</h1>',baseZ: 9999999999 })	
	param = $("#formulariogaleria").serialize(); 
	param += "&galeriacod="+$("#galeriacod").val(); 
	param += "&accion=2";
	
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				$("#mul_multimedia_previsualizar_am").html("");
				$("#mul_multimedia_subir_am").hide();
				CargarListado();
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

//ELIMINAR LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE GALERIA
function EliminarMultimedia(multimediacod)
{
	if (!confirm("Esta seguro que desea eliminar el archivo multimedia de la galeria?"))
		return false;
		
	$("#MsgGuardando").html("Eliminando...");
	$("#MsgGuardando").show();
	param = "&galeriacod="+$("#galeriacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=4";
	
	$(".msgacciongaleria").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "GET",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				$(".msgacciongaleria").html(msg.Msg);
				CargarListado();
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
	
}





/*AUDIOS*/


//GUARDA LA IMAGEN EN MULTIMEDIA Y ADEMAS 
//RELACIONA EL ID DE MULTIMEDIA CON EL ID DE NOTICIA
function SeleccionarAudioMultimedia(multimediacod)
{
	$("#MsgGuardando").html("Seleccionado audio");
	$("#MsgGuardando").show();
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Seleccionando audio...</h1>',baseZ: 9999999999 })	
	param = "galeriacod="+$("#galeriacod").val(); 
	param += "&multimediacod="+multimediacod; 
	param += "&accion=8";
	
	$(".msgacciongaleria").html("&nbsp;");
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_multimedia_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.success)
			{
				$(".msgacciongaleria").html(msg.Msg);
				$("#PopupMultimedia").dialog("close"); 
				CargarMultimedia(3,"#audios")
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
			 $.unblockUI();
	   }
	});
	
}

 
 
