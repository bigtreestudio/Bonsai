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
	jQuery("#listarDatos").jqGrid('setGridParam', {url:"plan_objetivos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear(){
	$("#planobjetivocod").val("");
	$("#planobjetivonombre").val("");
	timeoutHnd = setTimeout(gridReload,500);
}
function listar(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarDatos").jqGrid(
	{
		url:'plan_objetivos_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['C\u00f3digo','Nombre','Estado','Editar','Eliminar'],
		colModel:[
			{name:'planobjetivocod',index:'planobjetivocod', width:25, align:'center'},
			{name:'planobjetivonombre',index:'planobjetivonombre', align:'left'},
			{name:'act',index:'act', width:30,  align:'center', sortable:false},
			{name:'edit',index:'edit', width:30,  align:'center', sortable:false},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2',
		sortname: 'planobjetivocod',
		viewrecords: true,
		sortorder: "DESC", 
		height:290, 
		caption:"", 
		emptyrecords: "Sin datos para mostrar.", 
		loadError : function(xhr,st,err) { 
		} 
	});
	$(window).bind('resize', function() {
		$("#listarDatos").setGridWidth($("#LstDatos").width());
	}).trigger('resize');
	jQuery("#listarDatos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
function ActivarDesactivar(codigo,tipo){
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "planobjetivocod="+codigo;
	param += "&accion="+tipo;
	EnviarDatos(param);
}
function Eliminar(codigo){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "planobjetivocod="+codigo;
	param += "&accion=3";
	EnviarDatos(param);
}
function EnviarDatos(param){
	$.ajax({
		type: "POST",
		url: "plan_objetivos_upd.php",
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