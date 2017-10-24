jQuery(document).ready(function(){
	listarTipos();	
});
	
var timeoutHnd; 
function doSearchTipos(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadTipos,500) 
}

function gridReloadTipos(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTipos").jqGrid('setGridParam', {url:"tap_tapas_tipos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


function listarTipos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTipos").jqGrid(
	{ 
	
		url:'tap_tapas_tipos_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['COD','Nombre','Url','Nombre Archivo','Act/Desc','Edit','Del'], 
		colModel:[ {name:'tapatipocod',index:'tapatipocod', width:20, align:"center",sortable:false}, 
				  {name:'tapatipodesc',index:'tapatipodesc'},
				  {name:'tapatipourlfriendly',index:'tapatipourlfriendly', align:"left",width:50}, 							
				  {name:'tapatipoarchivo',index:'tapatipoarchivo', align:"left",width:50}, 							
				  {name:'act',index:'act', width:20, align:"center", sortable:false},
				  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
				  {name:'del',index:'del', width:20, align:"center", sortable:false},
			  ], 
		rowNum:20, 
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2', 
		sortname: 'tapatipocod', 
		viewrecords: true, 
		sortorder: "asc", 
		height:290,
		caption:"",
		emptyrecords: "Sin tipos para mostrar.",
		loadError : function(xhr,st,err) {
			  // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
				//alert("Error al procesar los datos");
		},
	
	}); 

	$(window).bind('resize', function() {
		$("#listarTipos").setGridWidth($("#LstTipos").width());
	}).trigger('resize');
		jQuery("#listarTipos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function FormTipos(modif,tapatipocod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando...</h1>',baseZ: 9999999999 })	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param += "&tapatipocod="+tapatipocod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_tipos_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 630, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Tipo de Portada", 
				open: function(type, data) {$("#Popup").html(msg);$(".chzn-select").chosen();$.unblockUI();}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}

function AltaTipo()
{
	FormTipos(0,'');
	return true;
}
	
function EditarTipo(tapatipocod)
{
	FormTipos(1,tapatipocod);
	return true;
}
	
	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_tipos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadTipos();
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

function EliminarTipo(tapatipocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el Tipo de portada?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando tipo...</h1>',baseZ: 9999999999 })	
	param = "tapatipocod="+tapatipocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(tapatipocod,tipo)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Procesando...</h1>',baseZ: 9999999999 })	
	var param;
	param = "tapatipocod="+tapatipocod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{

	 $("#Popup").dialog("close"); 
}

function InsertarTipo()
{
	var param;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Agregando tipo...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarTipo(tapatipocod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Modificando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
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



