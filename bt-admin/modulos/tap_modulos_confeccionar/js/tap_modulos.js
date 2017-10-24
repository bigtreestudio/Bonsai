jQuery(document).ready(function(){
	listarModulo();	
});
	
	var timeoutHnd; 
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarModulos").jqGrid('setGridParam', {url:"tap_modulos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarModulo()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarModulos").jqGrid(
	{ 

				url:'tap_modulos_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Modulo','Archivo','Act/Desc','Edit','Del'], 
				colModel:[ {name:'modulocod',index:'modulocod', width:20, align:"center"}, 
						  {name:'modulodesc',index:'modulodesc'}, 	
						  {name:'moduloarchivo',index:'moduloarchivo'},
						  {name:'act',index:'act', width:25, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:25, align:"center", sortable:false},
						  {name:'del',index:'del', width:25, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: '', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin Modulos para mostrar.",
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
				$("#listarModulos").setGridWidth($("#LstModulos").width());
			}).trigger('resize');
				jQuery("#listarModulos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}
	
function FormModulos(modif,modulocod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	
	if (modif)
		param += "&modulocod="+modulocod;
	$.ajax({
	   type: "POST",
	   url: "tap_modulos_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 480, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Modulos", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}

function AltaModulo()
{
	FormModulos(0,'');
	return true;
}
	
function EditarModulo(modulocod)
{
	
	FormModulos(1,modulocod);
	return true;
}
	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "tap_modulos_upd.php",
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

function EliminarModulo(modulocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el Modulo?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Eliminando modulo...</h1>',baseZ: 9999999999 })	
	param = "modulocod="+modulocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(modulocod,tipo)
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "modulocod="+modulocod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

function InsertarModulo()
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Agregando modulo...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarModulo(modulocod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	
	EnviarDatos(param);
	
	return true;
}





