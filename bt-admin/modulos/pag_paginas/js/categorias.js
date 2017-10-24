jQuery(document).ready(function(){
	ListarPaginasCategorias();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPaginasCategorias").jqGrid('setGridParam', {url:"pag_categorias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarCategoriasRelacionadas(orden)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "pag_categorias_upd.php",
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


function ListarPaginasCategorias()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPaginasCategorias").jqGrid(
	{ 

				url:'pag_categorias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Titulo','Estado','Orden','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'catnom',index:'catnom',sortable:false},
						  {name:'catestado',index:'catestado',align:"center", width:20,sortable:false}, 
						  {name:'orden',index:'orden', width:20, align:"center",sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center",sortable:false},
						  {name:'del',index:'del', width:20, align:"center",sortable:false}	
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
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarPaginasCategorias").setGridWidth($("#LstPaginasCategorias").width());
			}).trigger('resize');
				jQuery("#ListarPaginasCategorias").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
			jQuery("#ListarPaginasCategorias").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarPaginasCategorias").jqGrid("getDataIDs");
				   ReordenarCategoriasRelacionadas(neworder);
			   }}
			 );	
}

	
	function FormPagCat(modif,catsuperior,catcod)
	{
		var param, url;
		$("#cargando").show();
		param = "catsuperior="+catsuperior;
		if (modif)
			param += "&catcod="+catcod;
		$.ajax({
		   type: "POST",
		   url: "pag_categorias_am.php",
		   data: param,
		   success: function(msg){
				$("#Popup").dialog({	
					height: 550, 
					width: 550,
					zIndex: 999999999,  
					position: 'center', 
					title: "Categoria de pagina", 
					open: function(type, data) {$("#Popup").html(msg);$(".chzn-select").chosen();}
				});
				$("#cargando").hide();
		   }
		 });
	
		return true;
	}


	function AltaPagCat(catsuperior)
	{
		FormPagCat(0,catsuperior,0);
		return true;
	}
	function EditarPagCat(catcod,catsuperior)
	{
		FormPagCat(1,catsuperior,catcod);
		return true;
	}
	
	
	function ValidarJs()
	{
		if ($("#catnom").val()=="")
		{
			alert("Debe ingresar un nombre");
			$("#catnom").focus();
			return false;
		}
		if ($("#planthtmlcod").val()=="")
		{
			alert("Debe ingresar una plantilla");
			$("#catnom").focus();
			return false;
		}	
		return true;
	}
	
	
	function EnviarDatos(param)
	{
		$.ajax({
		   type: "POST",
		   url: "pag_categorias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
			if (msg.IsSuccess==true)
			{
				gridReload();
				alert(msg.Msg);
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
	
	function InsertarCategorias()
	{
		var param;
		if (!ValidarJs())
			return false;

		param = $("#formulario").serialize();
		param += "&accion=1";
		EnviarDatos(param);
		
		return true;
	}


	function ModificarCategorias()
	{
		var param;
		if (!ValidarJs())
			return false;

		param = $("#formulario").serialize();
		param += "&accion=2";
		EnviarDatos(param);
		
		return true;
	}


	function EliminarCategorias(catcod)
	{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar la categor\u00eda?"))
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
	
	
	function CargarMenu()
	{
		var param="tipo=4&menutipocod="+$("#menutipocod").val();
		$("#Menus").html("Cargando menu...");	
		$.ajax({
		   type: "POST",
		   url: "combo_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#Menus").html(msg);	 
				$(".chzn-select").chosen();
		   }
		   
		 });
	}

