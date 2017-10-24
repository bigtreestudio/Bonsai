// JavaScript Document

var mult_carga_multimedia_imagenes = false;
jQuery(document).ready(function(){
	
	CargarBuscadorImagenes();
	$('#mul_multimedia_fotos').tabs({
		select: function(e, ui) {

				CargarBuscadorImagenes();
		}
	});
});



function CargarBuscadorImagenes()
{
	if (mult_carga_multimedia_imagenes==false)
	{
		CargarBuscador();
		mult_carga_multimedia_imagenes = true;
	}
}

function CargarBuscador()
{
	jQuery("#TableMultimedia").jqGrid(
	{ 
		url:'mul_multimedia_popup_lst_ajax.php?multimediaconjuntocod=1&multimediacatcod=1&rand='+Math.random(),
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
		height:240,
		caption:"",
		emptyrecords: "Sin imagenes cargadas.",
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
		jQuery("#TableMultimedia").jqGrid('setGridParam', {url:"mul_multimedia_popup_lst_ajax.php?multimediaconjuntocod=1&multimediacatcod=1&rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
	} 
	
