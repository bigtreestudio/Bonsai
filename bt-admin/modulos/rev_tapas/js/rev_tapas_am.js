jQuery(document).ready(function(){
	initTextEditors();
	$("#revtapafecha").datepicker( {dateFormat:"dd/mm/yy"});
});
	

function InsertarRevTapa()
{
	var param;
	 var revtapadesc = tinyMCE.get('revtapadesc');
	$("#revtapadesc").val(revtapadesc.getContent());
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando tapa...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param,1);
	
	return true;
}

//va al upd con la accion 2
function ModificarRevTapa()
{
	 var revtapadesc = tinyMCE.get('revtapadesc');
	$("#revtapadesc").val(revtapadesc.getContent());
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	
	EnviarDatos(param,0);
	
	return true;
}

//va al upd con la accion 2
function PublicarFlip(revtapacod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Publicando...</h1>',baseZ: 9999999999 })	
	param = "revtapacod="+revtapacod;
	param += "&accion=8";
	
	EnviarDatos(param,0);
	
	return true;
}



var mult_carga_multimedia_imagenes = false;


//FUNCION QUE LLAMA AL UPD E INSERTA TODOS LOS DATOS TRAIDOS DEL FORMULARIO

function CrearBtUploadBanner(){
	 var argv = CrearBtUploadBanner.arguments;
	 var BotonId = argv[0];
	 var idImgMostrar = argv[1];
	
	  var uploader = new qq.FileUploader({
          element: document.getElementById(BotonId),
          action: 'rev_tapas_multimedia_upd.php?revtapacod='+$("#revtapacod").val(),
          multiple: false,
		  sizeLimit: sizeLimitFile, // max size
		  minSizeLimit: 0, // min size
		  allowedExtensions: ['jpg', 'png', 'gif'],
		  params: {tipo:1},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mover aquí para subir</span></div>' +
                        '<div class="qq-upload-button boton azul" style="height:17px;">Subir Tapa</div>' +
                        '<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list"></ul>' +                        
                    '</div>',   

         onComplete: function(id, fileName, responseJSON) {
				 var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 $(idImgMostrar).html(htmlmostrar);
				 $("#HtmlBtEliminar1").show();

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

function EliminarImagen()
{

	if(!confirm("Desea eliminar la Imagen de la tapa?"))
		return false;
		
	var param;
	param = $("#formulario").serialize();
	param += "&accion=7";
	$.blockUI({ message: '<h1 class="h1block"><img src="./images/cargando.gif" />Eliminando imagen de la tapa...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			$("#mul_multimedia_previsualizar").html("");
			$("#HtmlBtEliminar1").hide();
			alert(msg.Msg);
			$.unblockUI();	
		}
		 else
		{
			alert(msg.Msg);	 
			$.unblockUI();	
		}
		 
	   }
	   
	 });
	return true;
}

function EnviarDatos(param,recarga)
{
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			if (recarga==1)
				window.location=msg.header;
			else
				alert(msg.Msg)	
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

