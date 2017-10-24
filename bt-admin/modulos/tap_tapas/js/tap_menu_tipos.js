// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	listarTiposMenu();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposMenu").jqGrid('setGridParam', {url:"tap_menu_tipos_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}

function listarTiposMenu()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposMenu").jqGrid(
	{ 

				url:'tap_menu_tipos_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Archivo','Editar'], 
				colModel:[ {name:'menutipocod',index:'menutipocod', width:20, align:"center", hidden:true}, 
						  {name:'menutipodesc',index:'menutipodesc'},
						  {name:'menutipoarchivo',index:'menutipoarchivo'},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'menutipodesc', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin menu para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                       //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarTiposMenu").setGridWidth($("#LstTiposMenu").width());
			}).trigger('resize');
				jQuery("#ListarTiposMenu").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}



