$(document).ajaxStart($.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando Archivos...</h1>',baseZ: 9999999999  })).ajaxStop($.unblockUI);

jQuery(document).ready(function(){ListarFiles();});

//FUNCION QUE ARGMA LA GRILLA DE LOS DATOS DE LOS TOPS
//DATOS DE ENTRADA:
//      FILTROS DE BUSQUEDA: 
//				TOPDESC = DESCRIPCION DEL TOP
//				TOPTIPOCOD = TIPO DE TOP
//				ESTADO = ESTADO DEL TOP

function ListarFiles()
{
	jQuery("#ListarFiles").jqGrid(
	{ 

				url:'fil_config_lst_ajax.php?rand='+Math.random(),
				datatype: "json", 
				colNames:['COD','Nombre','Tipo','Url','Editar'], 
				colModel:[ {name:'filecod',index:'filecod', width:20, align:"center", hidden:true}, 
						  {name:'filenombre',index:'filenombre', sortable:false},
						  {name:'filetipodesc',index:'filetipodesc', width:70, sortable:false},
						  {name:'fileubic',index:'fileubic', width:70, sortable:false}, 
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				sortname: 'filecod', 
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
				$("#ListarFiles").setGridWidth($("#LstFiles").width());
			}).trigger('resize');
				jQuery("#ListarFiles").jqGrid('navGrid','',{edit:false,add:false,del:false,search:false,refresh:false});
}
