jQuery(document).ready(function(){
	Listar_plan_proyectos_comunas();
});

var timeoutHnd;
function doSearch_plan_proyectos_comunas(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload_plan_proyectos_comunas,500)
}

function gridReload_plan_proyectos_comunas(){
	var datos = $("#formbusqueda_plan_proyectos_comunas").serializeObject();
	jQuery("#listarDatos_plan_proyectos_comunas").jqGrid('setGridParam', {url:"plan_proyectos_comunas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear_plan_proyectos_comunas(){
	timeoutHnd = setTimeout(gridReload,500);
}

function Listar_plan_proyectos_comunas(){
	var datos = $("#formbusqueda_plan_proyectos_comunas").serializeObject();
	jQuery("#listarDatos_plan_proyectos_comunas").jqGrid(
	{
		url:'plan_proyectos_comunas_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Proyecto','Comuna','Barrios','Eliminar'],
		colModel:[
			{name:'planproyectocod',index:'planproyectocod', width:25, align:'center',hidden:true},
			{name:'comunacod',index:'comunacod', width:30, align:'left'},
			{name:'comunabarrios',index:'comunabarrios', align:'left'},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager_plan_proyectos_comunas',
		sortname: 'planproyectocod',
		viewrecords: true,
		sortorder: "DESC", 
		height:290, 
		caption:"", 
		emptyrecords: "Sin datos para mostrar.", 
		loadError : function(xhr,st,err) { 
		} 
	});

	$(window).bind('resize', function() {
		$("#listarDatos_plan_proyectos_comunas").setGridWidth($("#LstDatos_plan_proyectos_comunas").width());
	}).trigger('resize');

	jQuery("#listarDatos_plan_proyectos_comunas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}

function EliminarComuna(planproyectocod,comunacod){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "planproyectocod="+planproyectocod;
	param += "&comunacod="+comunacod;
	param += "&accion=3";
	EnviarDatos_plan_proyectos_comunas(param);

}

function AgregarComuna()
{
	if($("#formalta_plan_proyectos_comunas #comunacod").val()=="")
	{
		alert("Debe selecionar una comuna");
		return false;	
	}
	
	if (!confirm("Esta seguro que desea agregar la comuna?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 })	
	param = $("#formalta_plan_proyectos_comunas").serialize();
	param += "&accion=1";
	EnviarDatos_plan_proyectos_comunas(param);
	
}

function EnviarDatos_plan_proyectos_comunas(param){
	$.ajax({
		type: "POST",
		url: "plan_proyectos_comunas_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload_plan_proyectos_comunas();
				$("#formalta_plan_proyectos_comunas #comunacod").val("");
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