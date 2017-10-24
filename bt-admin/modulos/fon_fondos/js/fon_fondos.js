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
	jQuery("#listarDatos").jqGrid('setGridParam', {url:"fon_fondos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear(){
	$("#fondodesc").val("");
	timeoutHnd = setTimeout(gridReload,500);
}

function listar(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarDatos").jqGrid(
	{
		url:'fon_fondos_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Codigo','Nombre','Editar'],
		colModel:[
			{name:'fondocod',index:'fondocod',width:10, align:'left'},
			{name:'fondodesc',index:'fondodesc', align:'left'},
			{name:'edit',index:'edit', width:30,  align:'center', sortable:false},
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2',
		sortname: 'fondocod',
		viewrecords: true,
		sortorder: "asc", 
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

function Eliminar(codigo){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "fondocod="+codigo;
	param += "&accion=3";
	EnviarDatos(param);

}

function EnviarDatos(param){
	$.ajax({
		type: "POST",
		url: "fon_fondos_upd.php",
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