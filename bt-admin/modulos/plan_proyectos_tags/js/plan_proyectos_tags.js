jQuery(document).ready(function(){
	listar_plan_proyectos_tags();
});

var timeoutHnd;
function doSearch_plan_proyectos_tags(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload_plan_proyectos_tags,500)
}

function gridReload_plan_proyectos_tags(){
	var datos = $("#formbusqueda_plan_proyectos_tags").serializeObject();
	jQuery("#listarDatos_plan_proyectos_tags").jqGrid('setGridParam', {url:"plan_proyectos_tags_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear(){
	timeoutHnd = setTimeout(gridReload,500);
}

function listar_plan_proyectos_tags(){
	var datos = $("#formbusqueda_plan_proyectos_tags").serializeObject();
	jQuery("#listarDatos_plan_proyectos_tags").jqGrid(
	{
		url:'plan_proyectos_tags_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Proyecto','Tags','Eliminar'],
		colModel:[
			{name:'planproyectocod',index:'planproyectocod', width:25, align:'center', hidden:true},
			{name:'plantagcod',index:'plantagcod', align:'left'},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager_plan_proyectos_tags',
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
		$("#listarDatos_plan_proyectos_tags").setGridWidth($("#LstDatos_plan_proyectos_tags").width());
	}).trigger('resize');

	jQuery("#listarDatos_plan_proyectos_tags").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}

function EliminarTags(planproyectocod,plantagcod){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "planproyectocod="+planproyectocod;
	param += "&plantagcod="+plantagcod;
	param += "&accion=3";
	EnviarDatos_plan_proyectos_tags(param);

}

function AgregarTags()
{
	if($("#formalta_plan_proyectos_tags #plantagcod").val()=="")
	{
		alert("Debe selecionar un tags");
		return false;	
	}
	
	if (!confirm("Esta seguro que desea agregar el tags?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 })	
	param = $("#formalta_plan_proyectos_tags").serialize();
	param += "&accion=1";
	EnviarDatos_plan_proyectos_tags(param);
	
}

function EnviarDatos_plan_proyectos_tags(param){
	$.ajax({
		type: "POST",
		url: "plan_proyectos_tags_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload_plan_proyectos_tags();
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