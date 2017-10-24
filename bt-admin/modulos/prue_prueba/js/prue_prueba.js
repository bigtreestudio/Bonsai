jQuery(document).ready(function(){
	listar();
});
var timeoutHnd;
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500)
}
function gridReload(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarDatos").jqGrid('setGridParam', {url:"prue_prueba_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
	if(Filtrado())
	{
		$("#listarDatos").find(">tbody").sortable("disable");
	}
	else
	{
		$("#listarDatos").find(">tbody").sortable("enable");
	}
}
function Resetear(){
	$("#pruebacod").val("");
	$("#provinciacod").val("");
	$("#pruebatitulo").val("");
	timeoutHnd = setTimeout(gridReload,500);
}
function Filtrado(){
	if($("#pruebacod").val()!="")
		 return true;
	if($("#provinciacod").val()!="")
		 return true;
	if($("#pruebatitulo").val()!="")
		 return true;
	 return false;
}
function Reordenar(orden){
	$("#MsgGuardando").show();
	param  = "orden="+orden
	param += "&accion=6";
	var param, url;
	$.ajax({
		type: "POST",
		url: "prue_prueba_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
			
			}
			else
			{
				alert(msg.Msg);
			}
			$("#MsgGuardando").hide();
		}
	});
}
function listar(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarDatos").jqGrid(
	{
		url:'prue_prueba_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Estado','Editar','Eliminar'],
		colModel:[
			{name:'act',index:'act', width:30,  align:'center', sortable:false},
			{name:'edit',index:'edit', width:30,  align:'center', sortable:false},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:1000,
		ajaxGridOptions: {cache: false},
		mtype: "POST",
		sortname: 'pruebaorden',
		viewrecords: true,
		sortorder: "ASC", 
		height:290, 
		caption:"", 
		emptyrecords: "Sin datos para mostrar.", 
		loadError : function(xhr,st,err) { 
		} 
	});
	$(window).bind('resize', function() {
		$("#listarDatos").setGridWidth($("#LstDatos").width());
	}).trigger('resize');
	jQuery("#listarDatos").jqGrid('sortableRows',
		{ cursor: 'move',items: '.jqgrow:not(.unsortable)',
			update : function(e,ui) {
			var order = $("#listarDatos").jqGrid("getDataIDs");
			Reordenar(order);
		}}
	);
}
function ActivarDesactivar(codigo,tipo){
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "pruebacod="+codigo;
	param += "&accion="+tipo;
	EnviarDatos(param);
}
function Eliminar(codigo){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "pruebacod="+codigo;
	param += "&accion=3";
	EnviarDatos(param);
}
function EnviarDatos(param){
	$.ajax({
		type: "POST",
		url: "prue_prueba_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload();
				alert(msg.Msg);
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