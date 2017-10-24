jQuery(document).ready(function(){
	ListarGraficos();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	jQuery("#ListarGraficos").jqGrid('setGridParam', {url:"gra_graficos_busqueda_lst_ajax.php?rand="+Math.random(),page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarGraficos()
{
	jQuery("#ListarGraficos").jqGrid(
	{ 

				url:'gra_graficos_busqueda_lst_ajax.php?rand='+Math.random(),
				datatype: "json", 
				colNames:['COD','Titulo','Seleccionar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'graficotitulo',index:'graficotitulo', sortable:false},
						  {name:'sel',index:'sel', width:15, align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'albumorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:200,
				caption:"",
				emptyrecords: "Sin graficos para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                       alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarGraficos").setGridWidth($("#LstGraficos").width());
			}).trigger('resize');
				jQuery("#ListarGraficos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}




function SeleccionarGrafico(graficocod)
{
	var html = "<p>$$Tipo='cGraficos' Codigo='"+graficocod+"'$$</p>";
	var editor = $("#editorid").val();
	tinyMCE.execInstanceCommand(editor,"mceInsertContent",false,html);
	$("#PopupGrafico").dialog("close"); 
	return true;
}



