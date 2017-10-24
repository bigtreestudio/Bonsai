jQuery(document).ready(function(){
	ListarMacrosColumnasEstructuras();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMacrosColumnasEstructuras").jqGrid('setGridParam', {url:"tap_macros_columnas_estructuras_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarMacroColumnas(orden,columnacod)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&columnacod="+columnacod;
	param += "&accion=4";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_macros_columnas_estructuras_upd.php",
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

function ListarMacrosColumnasEstructuras()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMacrosColumnasEstructuras").jqGrid(
	{ 
				url:'tap_macros_columnas_estructuras_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Class','Editar','Eliminar'], 
				colModel:[ {name:'estructuracod',index:'estructuracod', width:20, align:"center", hidden:true}, 
  						  {name:'estructuradesc',index:'estructuradesc',sortable:false}, 
						  {name:'estructuraclass',index:'estructuraclass', width:60, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'e.colestructuraorden', 
				viewrecords: true, 
				sortorder: "ASC", 
				height:440,
				caption:"",
				emptyrecords: "Sin estructuras para mostrar.",
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
				$("#ListarMacrosColumnasEstructuras").setGridWidth($("#LstMacrosColumnasEstructuras").width());
			}).trigger('resize');
				jQuery("#ListarMacrosColumnasEstructuras").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			jQuery("#ListarMacrosColumnasEstructuras").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarMacrosColumnasEstructuras").jqGrid("getDataIDs");
				   columnacod= $("#columnacod").val();
				   ReordenarMacroColumnas(neworder,columnacod);
			   }}
			 );	
}

function FormMacrosColEstructuras(modif,colestructuracod,columnacod)
{
	var param, url;
	$("#cargando").show();
	param = "columnacod="+columnacod;
	if (modif)
		param += "&colestructuracod="+colestructuracod;
	$.ajax({
	   type: "POST",
	   url: "tap_macros_columnas_estructuras_am.php",
	   data: param,
	   success: function(msg){
			$("#ModalMacroColEs").dialog({	
				height: 200, 
				width: 550,
				zIndex: 999999999,  
				position: 'center', 
				title: "Macros", 
				open: function(type, data) {$("#ModalMacroColEs").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function AltaMacroColEstructura(columnacod)
{
	FormMacrosColEstructuras(0,0,columnacod);
	return true;
}
function EditMacroColEstructura(colestructuracod,columnacod)
{
	FormMacrosColEstructuras(1,colestructuracod,columnacod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_macros_columnas_estructuras_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert(msg.Msg);
			DialogCloseColEs(); 
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

function InsertarMacroColEstructura()
{
	
	var param;
	param = $("#formmacrocolestructura").serialize();
	param += "&accion=1";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}

function ModificarMacroColEstructura()
{
	
	var param;
	param = $("#formmacrocolestructura").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarMacroColEstructura(colestructuracod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la estructura?"))
		return false;
	param = "colestructuracod="+colestructuracod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function DialogCloseColEs()
{
	 $("#ModalMacroColEs").dialog("close"); 
}

