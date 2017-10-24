jQuery(document).ready(function(){
	ListarMacrosColumnas();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMacrosColumnas").jqGrid('setGridParam', {url:"tap_macros_columnas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarMacrosColumnas()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMacrosColumnas").jqGrid(
	{ 
				url:'tap_macros_columnas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Macro','Estructuras','Editar','Eliminar'], 
				colModel:[ {name:'macrocod',index:'macrocod', width:20, align:"center", hidden:true}, 
  						  {name:'macrodesc',index:'macrodesc',sortable:false}, 
						  {name:'link',index:'link', width:30, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'macrocod', 
				viewrecords: true, 
				sortorder: "ASC", 
				height:440,
				caption:"",
				emptyrecords: "Sin macros columnas para mostrar.",
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
				$("#ListarMacrosColumnas").setGridWidth($("#LstMacrosColumnas").width());
			}).trigger('resize');
				jQuery("#ListarMacrosColumnas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}

function FormMacrosColumna(modif,columnacod,estructuracod)
{
	var param, url;
	$("#cargando").show();
	param = "estructuracod="+estructuracod;
	if (modif)
		param += "&columnacod="+columnacod;
	$.ajax({
	   type: "POST",
	   url: "tap_macros_columnas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 200, 
				width: 550,
				zIndex: 999999999,  
				position: 'center', 
				title: "Macros Columnas", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function AltaMacrosColumna(estructuracod)
{
	FormMacrosColumna(0,0,estructuracod);
	return true;
}
function EditarMacrosColumna(columnacod,estructuracod)
{
	FormMacrosColumna(1,columnacod,estructuracod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_macros_columnas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert(msg.Msg);
			DialogCloseMacrosCol();
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

function InsertarMacrosColumna()
{
	
	var param;
	param = $("#formmacrocolumna").serialize();
	param += "&accion=1";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}

function ModificarMacrosColumna()
{
	
	var param;
	param = $("#formmacrocolumna").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarMacrosColumna(columnacod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la macro?"))
		return false;
	param = "columnacod="+columnacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}
function DialogCloseMacrosCol()
{
	$("#Popup").dialog("close"); 
}