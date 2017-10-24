jQuery(document).ready(function(){
	Listar_plan_proyectos_ejes();
});

var timeoutHnd;
function doSearch_plan_proyectos_ejes(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload_plan_proyectos_ejes,500)
}

function gridReload_plan_proyectos_ejes(){
	var datos = $("#formbusqueda_plan_proyectos_ejes").serializeObject();
	jQuery("#listarDatos_plan_proyectos_ejes").jqGrid('setGridParam', {url:"plan_proyectos_ejes_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear_plan_proyectos_ejes(){
	timeoutHnd = setTimeout(gridReload_plan_proyectos_ejes,500);
}

function Listar_plan_proyectos_ejes(){
	var datos = $("#formbusqueda_plan_proyectos_ejes").serializeObject();
	jQuery("#listarDatos_plan_proyectos_ejes").jqGrid(
	{
		url:'plan_proyectos_ejes_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Proyecto','Eje','Eliminar'],
		colModel:[
			{name:'planproyectocod',index:'planproyectocod', width:25, align:'center', hidden:true},
			{name:'planejecod',index:'planejecod', align:'left'},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager_plan_proyectos_ejes',
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
		$("#listarDatos_plan_proyectos_ejes").setGridWidth($("#LstDatos_plan_proyectos_ejes").width());
	}).trigger('resize');

	jQuery("#listarDatos_plan_proyectos_ejes").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}


function EliminarEjes(planproyectocod,planejecod){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "planproyectocod="+planproyectocod;
	param += "&planejecod="+planejecod;
	param += "&accion=3";
	EnviarDatos_plan_proyectos_ejes(param);

}


function AgregarEjes()
{
	if($("#formalta_plan_proyectos_ejes #planejecod").val()=="")
	{
		alert("Debe selecionar un eje");
		return false;	
	}
	
	if (!confirm("Esta seguro que desea agregar el eje?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 })	
	param = $("#formalta_plan_proyectos_ejes").serialize();
	param += "&accion=1";
	EnviarDatos_plan_proyectos_ejes(param);
	
}

function EnviarDatos_plan_proyectos_ejes(param){
	$.ajax({
		type: "POST",
		url: "plan_proyectos_ejes_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload_plan_proyectos_ejes();
				$("#formalta_plan_proyectos_ejes #planejecod").val("");
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