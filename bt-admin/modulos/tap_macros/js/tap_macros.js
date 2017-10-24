jQuery(document).ready(function(){
	ListarMacros();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMacros").jqGrid('setGridParam', {url:"tap_macros_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#formatodescbusqueda").val("");
	$("#formatoanchobusqueda").val("");
	$("#formatoaltobusqueda").val("");
	$("#formatocarpetabusqueda").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarMacros()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMacros").jqGrid(
	{ 
				url:'tap_macros_lst_ajax.php?rand='+Math.random(),
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
				emptyrecords: "Sin macros para mostrar.",
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
				$("#ListarMacros").setGridWidth($("#LstMacros").width());
			}).trigger('resize');
				jQuery("#ListarMacros").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}

function FormMacros(modif,macrocod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param += "&macrocod="+macrocod;
	$.ajax({
	   type: "POST",
	   url: "tap_macros_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 200, 
				width: 550,
				zIndex: 999999999,  
				position: 'center', 
				title: "Macros", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function AltaMacros(macrocod)
{
	FormMacros(0,0);
	return true;
}
function EditarMacros(macrocod)
{
	FormMacros(1,macrocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_macros_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert(msg.Msg);
			DialogCloseMacros();
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

function InsertarMacro()
{
	
	var param;
	param = $("#formmacro").serialize();
	param += "&accion=1";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}

function ModificarMacro()
{
	
	var param;
	param = $("#formmacro").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarMacro(macrocod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la macro?"))
		return false;
	param = "macrocod="+macrocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}
function DialogCloseMacros()
{
	$("#Popup").dialog("close"); 
}