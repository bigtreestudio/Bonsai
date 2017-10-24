// JavaScript Document
var raiz = "../../../../"
var mult_carga_multimedia_imagenes = false;
jQuery(document).ready(function(){
	setTimeout("CargarBuscador()",500) ;
});





function CargarBuscador()
{
	
	jQuery("#TableEditorMultimediaFoto").jqGrid(
	{ 
		url:raiz+'mul_multimedia_editor_popup_lst_ajax.php?multimediaconjuntocod=1&multimediacatcod=1&rand='+Math.random(),
		datatype: "json", 
		colNames:['Id', 'Imagen','Descripcion', 'Seleccionar'], 
		colModel:[ {name:'multimediacod',index:'multimediacod', hidden:true}, 
				  {name:'imagen',index:'imagen', align:"center",sortable:false}, 
				  {name:'multimediadesc',index:'multimediadesc',sortable:false}, 
				  {name:'edit',index:'edit', align:"center", sortable:false}
				  ], 
		rowNum:20, 
		ajaxGridOptions: {cache: false},
		mtype: "POST",
		sortname: 'multimediacod', 
		viewrecords: true, 
		sortorder: "desc", 
		height:130,
		width:450,
		caption:"",
		emptyrecords: "Sin imagenes cargadas.",
		loadError : function(xhr,st,err) {
				alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
		}
	}); 

	
}


function SeleccionarImagenMultimediaEditor(multimediacod)
{
	$("#src").val($("#srcurl_"+multimediacod).val());
	ImageDialog.showPreviewImage($("#srcurl_"+multimediacod).val());
}



var timeoutMult; 
function KeyPressBusquedaMultimedia(ev){ 
	if(timeoutMult) 
		clearTimeout(timeoutMult) 
	timeoutMult = setTimeout(gridReloadMultimedia,500) 
}
function gridReloadMultimedia(){ 
	var datos = "multimediadesc="+$("#multimedia_desc_busqueda").val();
	jQuery("#TableEditorMultimediaFoto").jqGrid('setGridParam', {url:raiz+"mul_multimedia_editor_popup_lst_ajax.php?multimediaconjuntocod=1&multimediacatcod=1&rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


function AgregarImagenTinyMce()
{
	var html = $("#ImagenSeleccionada").html();
	var editor = $("#editorid").val();
	tinyMCE.execInstanceCommand(editor,"mceInsertContent",false,html);
	$("#PopupMultimedia").dialog("close"); 
	return true;
	
}

