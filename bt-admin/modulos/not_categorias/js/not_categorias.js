jQuery(document).ready(function(){
	listarCategorias();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarCategorias").jqGrid('setGridParam', {url:"not_categorias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#ventacod").val("");
	$("#tipoventacod").val("");
	$("#metodopagocod").val("");
	$("#tipofacturacod").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarCategoriasRelacionadas(orden)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_categorias_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function listarCategorias()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarCategorias").jqGrid(
	{ 

				url:'not_categorias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Dominio','Widgets','Estado','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'catnom',index:'catnom', sortable:false},
						  {name:'catdominio',index:'catdominio',align:"center", sortable:false,width:40,},
						  {name:'catmodulos',index:'catmodulos',align:"center", width:20, sortable:false}, 
						  {name:'catestado',index:'catestado',align:"center", width:20, sortable:false}, 
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'catnom', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin categorias para mostrar.",
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
				$("#listarCategorias").setGridWidth($("#LstCategorias").width());
			}).trigger('resize');
				jQuery("#listarCategorias").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			jQuery("#listarCategorias").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#listarCategorias").jqGrid("getDataIDs");
				   ReordenarCategoriasRelacionadas(neworder);
			   }}
			 );	
}

	
	
	
	function EnviarDatos(param)
	{
		$.ajax({
		   type: "POST",
		   url: "not_categorias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
			if (msg.IsSuccess==true)
			{
				gridReload();
				$("#Popup").dialog("close"); 
				$.unblockUI();	
			}
			 else
			{
			 	alert(msg.Msg);	 
				$.unblockUI();	
			}
			 
		   }
		   
		 });
	}


	function EliminarCategorias(catcod)
	{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar la categor\u00eda?"))
			return false;
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando categoria...</h1>',baseZ: 99999999999});
		param = "catcod="+catcod;
		param += "&accion=3";
		EnviarDatos(param);
	
		return true;
	}

	function ActivarDesactivar(catcod,tipo)
	{
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando...</h1>',baseZ: 99999999999});
		var param;
		param = "catcod="+catcod;
		param += "&accion="+tipo;
		EnviarDatos(param);
	}
