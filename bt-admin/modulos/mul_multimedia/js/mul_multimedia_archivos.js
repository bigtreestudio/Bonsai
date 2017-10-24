// JavaScript Document

var mult_carga_multimedia_archivos = false;
jQuery(document).ready(function(){
	$('#mul_multimedia_fotos').tabs({
		select: function(e, ui) {
			var thistab = ui;
			if (thistab.index==1)
				CargarBuscadorArchivos();
		}
	});
	CrearBtUploadArchivos("mul_multimedia_bt_subir_file","#mul_multimedia_previsualizar","#mul_multimedia_descripcion","#mul_multimedia_size","#mul_multimedia_name","#mul_multimedia_file","");
});



function CrearBtUploadArchivos(){
	 var argv = CrearBtUploadArchivos.arguments;
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
		  allowedExtensions: ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt' ],
		  params: {tipo:1},
          template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area" style="display: none;"><span>Mover aquí para subir</span></div>' +
                        '<div class="qq-upload-button boton verde" style="height:17px;">Subir un archivo</div>' +
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


function CargarBuscadorArchivos()
{
	if (mult_carga_multimedia_archivos==false)
	{
		CargarBuscador();
		mult_carga_multimedia_archivos = true;
	}
}

function CargarBuscador()
{
	jQuery("#TableMultimedia").jqGrid(
	{ 
		url:'mul_multimedia_popup_lst_ajax.php?multimediaconjuntocod=4&rand='+Math.random(),
		datatype: "json", 
		colNames:['Id', 'Archivo','Descripcion', 'Seleccionar'], 
		colModel:[ {name:'multimediacod',index:'multimediacod', width:30, hidden:true}, 
				  {name:'imagen',index:'imagen',width:20 , sortable:true}, 
				  {name:'multimediadesc',index:'multimediadesc'}, 
				  {name:'edit',index:'edit', width:30, align:"center", sortable:false}
				  ], 
		rowNum:20, 
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pagermultimedia', 
		sortname: 'multimediacod', 
		viewrecords: true, 
		sortorder: "desc", 
		height:240,
		caption:"",
		emptyrecords: "Sin archivos cargados.",
		loadError : function(xhr,st,err) {
				alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
		}
	}); 

	$(window).bind('resize', function() {
		$("#TableMultimedia").setGridWidth($("#TableMultimediawidth").width());
	}).trigger('resize');

	jQuery("#TableMultimedia").jqGrid('navGrid','#pagermultimedia',{edit:false,add:false,del:false,search:false,refresh:false});
	
	
}

	var timeoutMult; 
	function KeyPressBusquedaMultimedia(ev){ 
		if(timeoutMult) 
			clearTimeout(timeoutMult) 
		timeoutMult = setTimeout(gridReloadMultimedia,500) 
	}
	function gridReloadMultimedia(){ 
		var datos = $("#formbusquedamultimedia").serializeObject(); 
		jQuery("#TableMultimedia").jqGrid('setGridParam', {url:"mul_multimedia_popup_lst_ajax.php?multimediaconjuntocod=4&rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
	} 

var timeoutHnd
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadMultimedia,500) 
}	

