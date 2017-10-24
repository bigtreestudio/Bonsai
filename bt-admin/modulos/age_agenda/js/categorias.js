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
	jQuery("#listarCategorias").jqGrid('setGridParam', {url:"age_agenda_categorias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	

function ReordenarCategoriasRelacionadas(orden)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_categorias_upd.php",
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

				url:'age_agenda_categorias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Estado','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'catnom',index:'catnom', sortable:false},
						  {name:'catestado',index:'catestado',align:"center", width:15, sortable:false}, 
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
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

	
	function FormCategorias(modif,catsuperior,catcod)
	{
		var param, url;
		$("#cargando").show();
		param = "catsuperior="+catsuperior;
		if (modif)
			param += "&catcod="+catcod;
		$.ajax({
		   type: "POST",
		   url: "age_agenda_categorias_am.php",
		   data: param,
		   success: function(msg){
				$("#Popup").dialog({	
					height: 400, 
					width: 550, 
					position: 'center', 
					modal:true,
					title: "Categorias", 
					open: function(type, data) {$("#Popup").html(msg);}
				});
				$("#cargando").hide();
		   }
		 });
	
		return true;
	}


	function AltaCategorias(catsuperior)
	{
		FormCategorias(0,catsuperior,0);
		return true;
	}
	function EditarCategorias(catcod,catsuperior)
	{
		FormCategorias(1,catsuperior,catcod);
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
			return false;
		}	
		return true;
	}
	
	
	function EnviarDatos(param)
	{
		$.ajax({
		   type: "POST",
		   url: "age_agenda_categorias_upd.php",
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

