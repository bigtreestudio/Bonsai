jQuery(document).ready(function(){
	ListarWorkflow();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarWorkflow").jqGrid('setGridParam', {url:"pag_paginas_workflow_roles_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#rolcodbusqueda").val("");
	$("#pagestadocodbusqueda").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarWorkflow()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarWorkflow").jqGrid(
	{ 
				url:'pag_paginas_workflow_roles_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','COD2','Rol','Estado','Accion','Eliminar'], 
				colModel:[ {name:'rolcod',index:'rolcod', width:20, align:"center", hidden:true}, 
						  {name:'paginaworkflowcod',index:'paginaworkflowcod', width:20, align:"center", hidden:true}, 
						  {name:'roldesc',index:'roldesc'},
						  {name:'paginaestadocodinicial',index:'paginaestadocodinicial'}, 
						  {name:'paginaestadodescinicial',index:'paginaestadodescinicial',sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'r.rolcod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin acciones cargadas.",
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
				$("#ListarWorkflow").setGridWidth($("#LstWorkflow").width());
			}).trigger('resize');
				jQuery("#ListarWorkflow").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

function CargarEstados()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando estados disponibles...</h1>' ,baseZ: 9999999999})	
	var param, url;
	param = 'tipo=1';
	param += "&rolcod="+$("#rolcod").val();
	$("#cargando").show();
		$.ajax({
		   type: "POST",
		   url: "pag_paginas_workflow_roles_datos_ajax.php",
		   data: param,
		   success: function(msg){
			 $("#Estados").html(msg);
			 $.unblockUI();
			 $("#cargando").hide();
			 $("#Acciones").html("");
		   }
		 });
	
		return true;
}

function CargarAcciones()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando acciones disponibles...</h1>' ,baseZ: 9999999999 })	
	var param, url;
	param = 'tipo=2';
	param += "&pagestadocod="+$("#pagestadocod").val();
	param += "&rolcod="+$("#rolcod").val();
	$("#cargando").show();
		$.ajax({
		   type: "POST",
		   url: "pag_paginas_workflow_roles_datos_ajax.php",
		   data: param,
		   success: function(msg){
			 $("#Acciones").html(msg);
			 $.unblockUI();
			 $("#cargando").hide();
			
		   }
		 });
	
		return true;
}

function FormWorkflow()
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_workflow_roles_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 380, 
				width: 750,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Acciones", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaWorkflow()
{
	FormWorkflow();
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_workflow_roles_upd.php",
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

function InsertarWorkflow()
{
	var param;
	param = $("#formworkflow").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}


function EliminarWorkflow(rolcod,paginaworkflowcod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la accion?"))
		return false;
	param = "rolcod="+rolcod;
	param += "&paginaworkflowcod="+paginaworkflowcod;
	param += "&accion=2";
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}