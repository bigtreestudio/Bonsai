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
	var datos = $("#formbusquedacategoria").serializeObject();
	jQuery("#listarCategorias").jqGrid('setGridParam', {url:"lin_categorias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarCategorias(catorden)
{	
	$("#MsgGuardando").show();
	 
	param  = "catorden="+catorden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "lin_categorias_upd.php",
	   data: param,
	   dataType:"json",
	   
	   success: function(msg){ 
			//alert(msg);
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

				url:'lin_categorias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Links','Estado','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'catnom',index:'catnom'},
						  {name:'links',index:'links',align:"center",width:20},
						  {name:'catestado',index:'catestado',align:"center", width:20}, 
						  {name:'edit',index:'edit', width:20, align:"center"},
						  {name:'del',index:'del', width:20, align:"center"}	
						  
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'catorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin categorias para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
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
				   ReordenarCategorias(neworder);
			   }}
			 );	
}

	
	function FormCategorias(modif,catcod)
	{
		var param, url;
		$("#cargando").show();
		param = "";
		if (modif)
			param += "&catcod="+catcod;
		$.ajax({
		   type: "POST",
		   url: "lin_categorias_am.php",
		   data: param,
		   success: function(msg){
				$("#Popup").dialog({	
					zIndex: 9999999999,
					height: 400, 
					width: 550, 
					position: 'center', 
					modal:false,
					title: "Categorias", 
					open: function(type, data) {$("#Popup").html(msg);}
				});
				$("#cargando").hide();
		   }
		 });
	
		return true;
	}


	function AltaCategorias()
	{
		FormCategorias(0,'');
		return true;
	}
	
	function EditarCategorias(catcod)
	{
		FormCategorias(1,catcod);
		return true;
	}
	
	function EnviarDatos(param)
	{
		$.ajax({
		   type: "POST",
		   url: "lin_categorias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
			if (msg.IsSuccess==true)
			{
				gridReload();
				//alert(msg.Msg);
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
	

	function EliminarCategoria(catcod)
	{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar la categoria?"))
			return false;
		param = "catcod="+catcod;
		param += "&accion=3";
		EnviarDatos(param);
	
		return true;
	}

	function ActivarDesactivar(catcod,tipo)
	{
		var param;
		param = "catcod="+catcod;
		param += "&accion="+tipo;
		EnviarDatos(param);
	}
	
	function DialogClose()
	{
		 $("#Popup").dialog("close"); 
	}

	function InsertarCategorias()
	{
		var param;
		
		param = $("#formulariocategorias_am").serialize();
		param += "&accion=1";
		EnviarDatos(param);
		
		return true;
	}
	//va al upd con la accion 2
	function ModificarCategorias(catcod)
	{
	
		param = $("#formulariocategorias_am").serialize();
		param += "&accion=2";
		EnviarDatos(param);
		
		return true;
	}