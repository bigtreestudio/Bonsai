jQuery(document).ready(function(){
	$(".chzn-select").chosen(); initTextEditors();
	if ($("#catcod").val()!="")
	{
		CrearBtUploadImg()
		
	}
});
	
	
	
function CrearBtUploadImg(){
	  var uploader = new qq.FileUploader({
          element: document.getElementById("btn_subirImgMostrar"),
		  action: 'not_categorias_multimedia_carga.php?catcod='+$("#catcod").val(),
          multiple: false,
		  allowedExtensions: ['jpg', 'png', 'gif'],
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mueva el archivo aqu&iacute;</span></div>' +
						'<div class="qq-upload-button boton verde" style="cursor:pointer;">Subir Imagen</div>' +
						'<div class="qq-upload-size"></div>' +
                        '<ul class="qq-upload-list" style="display:none;"></ul>' +                     
                    '</div>', 
					failUploadText: 'File Already Exist.',
        onComplete: function(id, fileName, responseJSON) {
				 var htmlmostrar = responseJSON.archivo.replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>');
				 $("#ImgFondo").html(htmlmostrar);
				 
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
	
	
	
function ValidarJs()
{
	if ($("#catnom").val()=="")
	{
		alert("Debe ingresar un nombre");
		$("#catnom").focus();
		return false;
	}

	return true;
}


function EnviarDatos(param,accion)
{
	$.ajax({
	   type: "POST",
	   url: "not_categorias_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			if (accion==1)
				window.location=msg.header;
			$(".msgaccionupd").html(msg.Msg);
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

function InsertarCategorias()
{
	var param;
	if (!ValidarJs())
		return false;

	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Agregando categoria...</h1>',baseZ: 99999999999});
	var catdesc = tinyMCE.get('catdesc');
	$("#catdesc").val(catdesc.getContent());
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param,1);
	
	return true;
}


function ModificarCategorias()
{
	var param;
	if (!ValidarJs())
		return false;

	//$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Modificando categoria...</h1>',baseZ: 99999999999});
	var catdesc = tinyMCE.get('catdesc');
	$("#catdesc").val(catdesc.getContent());
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param,2);
	
	return true;
}


function CargarMenu()
{
	var param="tipo=4&menutipocod="+$("#menutipocod").val();
	$("#Menus").html("Cargando menu...");	
	$.ajax({
	   type: "POST",
	   url: "combo_ajax.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#Menus").html(msg);	 
			$(".chzn-select").chosen();
	   }
	   
	 });
}
