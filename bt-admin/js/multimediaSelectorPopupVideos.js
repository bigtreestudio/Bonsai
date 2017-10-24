// JavaScript Document

jQuery(document).ready(function(){
	var campodevolucion = $("#campodevolucion").val();
	$('#mul_multimedia_videos_'+campodevolucion).tabs({
		select: function(e, ui) {
			var thistab = ui;
			if (thistab.index==2)
				CargarBuscadorSimpleVideos();
		}
	});
	CrearBtUploadVideos("mul_multimedia_bt_subir_video","#mul_multimedia_previsualizar_video","#mul_multimedia_descripcion_video","#mul_multimedia_size","#mul_multimedia_name","#mul_multimedia_file","");
});


var cargomultimediavideos = false;
function CargarBuscadorSimpleVideos()
{
	if (cargomultimediavideos==false)
	{
		CargarBuscadorSimple();
		cargomultimediavideos = true;
	}
}


function BuscarVideoExterno()
{
	
	$("#MsgGuardando").html("Buscando video...");
	$("#MsgGuardando").show();
	
	param = $("#form_mul_multimedia_video").serialize(); 

	var param, url;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_videos_externos_previsualizacion.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#mul_multimedia_previsualizar").html(msg);
			$("#mul_multimedia_descripcion").show();
			$("#MsgGuardando").hide();
			$("#MsgGuardando").html("Guardando...");
	   }
	});
}

function CargarBuscadorSimple()
{
	var param = 'multimediaconjuntocod=2&multimediacatcod=1&rand='+Math.random()+"&js=SeleccionarMultimediaSimpleVideos";
	var campodevolucion = $("#campodevolucion").val();
	jQuery("#TableMultimedia_"+campodevolucion).jqGrid(
	{ 
		url:'mul_multimedia_popup_lst_ajax.php?'+param,
		datatype: "json", 
		colNames:['Id', 'Imagen','Descripcion', 'Seleccionar'], 
		colModel:[ {name:'multimediacod',index:'multimediacod', width:30, hidden:true}, 
				  {name:'imagen',index:'imagen',width:20 , sortable:true}, 
				  {name:'multimediadesc',index:'multimediadesc'}, 
				  {name:'edit',index:'edit', width:30, align:"center", sortable:false}
				  ], 
		rowNum:20, 
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pagermultimedia_'+campodevolucion, 
		sortname: 'multimediacod', 
		viewrecords: true, 
		sortorder: "desc", 
		height:240,
		caption:"",
		emptyrecords: "Sin videos cargados.",
		loadError : function(xhr,st,err) {
				alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
		}
	}); 

	$(window).bind('resize', function() {
		$("#TableMultimedia_"+campodevolucion).setGridWidth($("#TableMultimediawidth_"+campodevolucion).width());
	}).trigger('resize');

	jQuery("#TableMultimedia_"+campodevolucion).jqGrid('navGrid','#pagermultimedia_'+campodevolucion,{edit:false,add:false,del:false,search:false,refresh:false});
	
	
}

	var timeoutMult; 
	function KeyPressBusquedaMultimedia(ev){ 
		if(timeoutMult) 
			clearTimeout(timeoutMult) 
		timeoutMult = setTimeout(gridReloadMultimedia,500) 
	}
	function gridReloadMultimedia(){ 
		var campodevolucion = $("#campodevolucion").val();
		var datos = $("#formbusquedamultimedia").serializeObject()+"&js=SeleccionarMultimediaSimpleVideos";
		jQuery("#TableMultimedia_"+campodevolucion).jqGrid('setGridParam', {url:"mul_multimedia_popup_lst_ajax.php?"+param, postData: datos,page:1}).trigger("reloadGrid"); 
	} 
	
var timeoutHnd
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadMultimedia,500) 
}	

function MostrarCajaYouTube(){
	$("#cajayoutube").show();
	$("#cajavimeo").hide();
	$("#cajavimeo #mulcodepage").val("");
}

function MostrarCajaVimeo(){
	$("#cajavimeo").show();
	$("#cajayoutube").hide();
	$("#cajayoutube #mulcodepage").val("");
}


function es_youtube($url)
{
  return (preg_match('/youtu\.be/i', $url) || preg_match('/youtube\.com\/watch/i', $url));
}

function es_vimeo($url)
{
  return (preg_match('/vimeo\.com/i', $url));
}

function Youtube_video_id($url)
{
  if(es_youtube($url))
  {
    $pattern = '/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/';
    preg_match($pattern, $url, $matches);
    if (count($matches) && strlen($matches[7]) == 11)
    {
      return $matches[7];
    }
  }
 
  return '';
}

function Vimeo_video_id($url)
{
  if(es_vimeo($url))
  {
    $pattern = '/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/';
    preg_match($pattern, $url, $matches);
    if (count($matches))
    {
      return $matches[2];
    }
  }
 
  return '';
}



function CrearBtUploadVideos(){
	 var argv = CrearBtUploadVideos.arguments;
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
		  sizeLimit: sizeLimitFile, // max size
		  minSizeLimit: 0, // min size
		  allowedExtensions: ['mp4','flv'],
		  params: {tipo:3},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mover aquí para subir</span></div>' +
                        '<div class="qq-upload-button boton verde" style="height:17px;">Seleccionar y subir un video</div>' +
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
				typeError: "{file} extensión invalida. Se aceptan solo {extensions}.",
				sizeError: "{file}: el archivo es demasiado grande, solo se aceptan menores a {sizeLimit}.",
				minSizeError: "{file} archivo vacio.",
				emptyError: "{file} archivo vacio.",
				onLeave: "Los archivos se están cargando, si cerras ahora la carga se cancelará."            
		  },
          debug: false
      });          
}





