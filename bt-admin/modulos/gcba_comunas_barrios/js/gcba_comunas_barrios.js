jQuery(document).ready(function(){
	$(".chzn-select").chosen(); 
	listar();
});

var timeoutHnd;
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500)
}

function gridReload(){
	var datos = $("#formbusqueda_gcba_comunasbarrios").serializeObject();
	jQuery("#listarDatos_gcba_comunasbarrios").jqGrid('setGridParam', {url:"gcba_comunas_barrios_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid");
}
function Resetear(){
	$("#comunabarriocod").val("");
	$("#comunacod").val("");
	$("#barriocod").val("");
	timeoutHnd = setTimeout(gridReload,500);
}

function listar(){
	var datos = $("#formbusqueda_gcba_comunasbarrios").serializeObject();
	jQuery("#listarDatos_gcba_comunasbarrios").jqGrid(
	{
		url:'gcba_comunas_barrios_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['Codigo','Barrio','Editar','Eliminar'],
		colModel:[
			{name:'comunabarriocod',index:'comunabarriocod', width:25, align:'center'},
			{name:'barriocod',index:'barriocod', align:'left'},
			{name:'edit',index:'edit', width:30,  align:'center', sortable:false, hidden:true},
			{name:'del',index:'del', width:30,  align:'center', sortable:false}
		], 
		rowNum:20,
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2',
		sortname: 'comunabarriocod',
		viewrecords: true,
		sortorder: "DESC", 
		height:290, 
		caption:"", 
		emptyrecords: "Sin datos para mostrar.", 
		loadError : function(xhr,st,err) { 
		} 
	});

	$(window).bind('resize', function() {
		$("#listarDatos_gcba_comunasbarrios").setGridWidth($("#LstDatos_gcba_comunasbarrios").width());
	}).trigger('resize');

	jQuery("#listarDatos_gcba_comunasbarrios").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}

function AgregarBarrio()
{
	if($("#formalta_gcba_comunasbarrios #barriocod").val()=="")
	{
		alert("Debe selecionar un barrio");
		return false;	
	}
	
	if (!confirm("Esta seguro que desea agregar el barrio?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Agregando...</h1>',baseZ: 9999999999 })	
	param = $("#formalta_gcba_comunasbarrios").serialize();
	param += "&accion=1";
	EnviarDatos_gcba_comunas_barrios(param);
	
}


function Eliminar(codigo){
	if (!confirm("Esta seguro que desea eliminar?"))
		return false;
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando...</h1>',baseZ: 9999999999 })	
	param = "comunabarriocod="+codigo;
	param += "&accion=3";
	EnviarDatos_gcba_comunas_barrios(param);

}

function EnviarDatos_gcba_comunas_barrios(param){
	$.ajax({
		type: "POST",
		url: "gcba_comunas_barrios_upd.php",
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