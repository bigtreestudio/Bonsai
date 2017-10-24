$(document).ajaxStart($.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando top...</h1>',baseZ: 9999999999  })).ajaxStop($.unblockUI);


var timeoutHnd; 
//FUNCION QUE REALIZA LA BUSQUEDA DE LOS CAMPOS INGRESADOS EN EL KEYPRESS

function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

//FUNCION QUE RECARGA LA GRILLA

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoTop").jqGrid('setGridParam', {url:"top_top_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 
    

//FUNCION QUE LIMPIA LOS CAMPOS DE BUSQUEDA

function Resetear()
{
	$("#topdesc").val("");
	$("#toptipocod").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


//FUNCION QUE ARGMA LA GRILLA DE LOS DATOS DE LOS TOPS
//DATOS DE ENTRADA:
//      FILTROS DE BUSQUEDA: 
//				TOPDESC = DESCRIPCION DEL TOP
//				TOPTIPOCOD = TIPO DE TOP
//				ESTADO = ESTADO DEL TOP

function listartop()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoTop").jqGrid(
	{ 

				url:'top_top_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripción','Tipo de Top','Url','Editar'], 
				colModel:[ {name:'topcod',index:'topcod', width:20, align:"center"}, 
						  {name:'topdesc',index:'topdesc'},
						  {name:'toptipocod',index:'toptipocod', width:40},
						  {name:'topurl',index:'topurl', width:70}, 
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'topcod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:280,
				caption:"",
				emptyrecords: "Sin tops cargados.",
				loadError : function(xhr,st,err) {
                        alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                }
			}); 
	
			$(window).bind('resize', function() {
				$("#ListadoTop").setGridWidth($("#LstTop").width());
			}).trigger('resize');
				jQuery("#ListadoTop").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
