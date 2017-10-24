jQuery(document).ready(function(){
	ListarPlantillasHTML();			
});

var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPlantillas").jqGrid('setGridParam', {url:"tap_plantillas_html_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#planthtmldesc").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarPlantillasHTML()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPlantillas").jqGrid(
	{ 
				url:'tap_plantillas_html_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripcion','Default','Editar','Archivos'], 
				colModel:[ {name:'planthtmlcod',index:'planthtmlcod', width:20, align:"center", hidden:true}, 
  						  {name:'planthtmldesc',index:'planthtmldesc',sortable:false}, 
  						  {name:'planthtmldefault',index:'planthtmldefault', align:"center",width:20,sortable:false}, 
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'editarch',index:'editarch', width:30, align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'planthtmlcod', 
				viewrecords: true, 
				sortorder: "ASC", 
				height:440,
				caption:"",
				emptyrecords: "Sin plantillas para mostrar.",
				loadError : function(xhr,st,err) {
                       //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarPlantillas").setGridWidth($("#LstPlantillas").width());
			}).trigger('resize');
				jQuery("#ListarPlantillas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}




