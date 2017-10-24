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
	jQuery("#ListarWorkflow").jqGrid('setGridParam', {url:"not_noticias_workflow_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#noticiaestadocodinicial").val("");
	$("#noticiaestadocodfinal").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarWorkflow()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarWorkflow").jqGrid(
	{ 
				url:'not_noticias_workflow_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Estado Inicial','Estado Final','Accion','Editar','Eliminar'], 
				colModel:[ {name:'noticiaworkflowcod',index:'noticiaworkflowcod', width:10, align:"center", hidden:true}, 
						  {name:'noticiaestadocodinicial',index:'noticiaestadocodinicial'},
						  {name:'noticiaestadocodfinal',index:'noticiaestadocodfinal'},
						  {name:'noticiaaccion',index:'noticiaaccion'}, 
						  {name:'edit',index:'edit',width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'noticiaworkflowcod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin workflow para mostrar.",
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
				$("#ListarWorkflow").setGridWidth($("#LstWorkflow").width());
			}).trigger('resize');
				jQuery("#ListarWorkflow").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

/*function CargarEstados()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando estados disponibles...</h1>' ,baseZ: 9999999999})	
	var param, url;
	param = 'tipo=1';
	param += "&rolcod="+$("#rolcod").val();
	$("#cargando").show();
		$.ajax({
		   type: "POST",
		   url: "not_noticias_workflow_roles_datos_ajax.php",
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
	param += "&noticiaestadocod="+$("#noticiaestadocod").val();
	param += "&rolcod="+$("#rolcod").val();
	$("#cargando").show();
		$.ajax({
		   type: "POST",
		   url: "not_noticias_workflow_roles_datos_ajax.php",
		   data: param,
		   success: function(msg){
			 $("#Acciones").html(msg);
			 $.unblockUI();
			 $("#cargando").hide();
			
		   }
		 });
	
		return true;
}*/

function FormWorkflow(modif,noticiaworkflowcod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if(modif)
		param = "noticiaworkflowcod="+noticiaworkflowcod;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_workflow_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 350, 
				width: 450,
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
	FormWorkflow(0,0);
	return true;
}

function EditarWorkflow(noticiaworkflowcod)
{
	FormWorkflow(1,noticiaworkflowcod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "not_noticias_workflow_upd.php",
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
function ModificarWorkflow()
{
	var param;
	param = $("#formworkflow").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarWorkflow(noticiaworkflowcod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la accion?"))
		return false;
	param = "noticiaworkflowcod="+noticiaworkflowcod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}