jQuery(document).ready(function(){
	ListarVisualizaciones();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarVisualizaciones").jqGrid('setGridParam', {url:"vis_visualizaciones_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#visualizaciondesc").val("");
	$("#visualizaciontipocod").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarVisualizaciones()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarVisualizaciones").jqGrid(
	{ 
				url:'vis_visualizaciones_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Tipo Visualizaci\u00f3n','Act/Desc','Editar','Eliminar'], 
				colModel:[ {name:'visualizacioncod',index:'visualizacioncod', width:20, align:"center", hidden:true}, 
						  {name:'visualizaciondesc',index:'visualizaciondesc'}, 
  						  {name:'visualizaciontipocod',index:'visualizaciontipocod'}, 
						  {name:'act/desc',index:'act/desc', width:40, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'visualizaciondesc', 
				viewrecords: true, 
				sortorder: "desc", 
				height:440,
				caption:"",
				emptyrecords: "Sin visualizaciones para mostrar.",
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
				$("#ListarVisualizaciones").setGridWidth($("#LstVisualizaciones").width());
			}).trigger('resize');
				jQuery("#ListarVisualizaciones").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}


function FormVisualizacion(modif,visualizacioncod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "visualizacioncod="+visualizacioncod;
	
	$.ajax({
	   type: "POST",
	   url: "vis_visualizaciones_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 270, 
				width: 350,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Visualizaci\u00f3n", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaVisualizacion()
{
	FormVisualizacion(0,0);
	return true;
}

function EditVisualizacion(visualizacioncod)
{
	FormVisualizacion(1,visualizacioncod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "vis_visualizaciones_upd.php",
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

function InsertarVisualizacion()
{
	var param;
	param = $("#formvisualizacion").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function ModificarVisualizacion()
{
	var param;
	param = $("#formvisualizacion").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function EliminarVisualizacion(visualizacioncod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la visualizaci\u00f3n?"))
		return false;
	param = "visualizacioncod="+visualizacioncod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(visualizacioncod,tipo)
{
	
	var param;
	param = "visualizacioncod="+visualizacioncod;
	param += "&accion="+tipo;
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}