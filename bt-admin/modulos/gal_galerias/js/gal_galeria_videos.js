// JavaScript Document

jQuery(document).ready(function(){
	CargarBuscador();	
});


var cargomultimediavideos = false;
function CargarBuscadorVideos()
{
	if (cargomultimediavideos==false)
	{
		CargarBuscador();
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

function CargarBuscador()
{
	jQuery("#TableMultimedia").jqGrid(
	{ 
		url:'mul_multimedia_popup_lst_ajax.php?multimediaconjuntocod=2&multimediacatcod=1&rand='+Math.random(),
		datatype: "json", 
		colNames:['Id', 'Imagen','Descripcion', 'Agregar'], 
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
		height:310,
		caption:"",
		emptyrecords: "Sin videos cargados.",
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
		jQuery("#TableMultimedia").jqGrid('setGridParam', {url:"mul_multimedia_popup_lst_ajax.php?multimediaconjuntocod=2&multimediacatcod=1&rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
	} 
	
