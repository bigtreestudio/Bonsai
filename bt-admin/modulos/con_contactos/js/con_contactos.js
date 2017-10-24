jQuery(document).ready(function(){
	listarContactos();	
});
	
var timeoutHnd; 
	
function doSearchFormContacto(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadContactos,500) 
}

function gridReloadContactos(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarContactos").jqGrid('setGridParam', {url:"con_contactos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


function listarContactos()
{
	
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarContactos").jqGrid(
	{ 
		url:'con_contactos_lst_ajax.php?rand='+Math.random(),
		postData: datos,
		datatype: "json", 
		colNames:['COD','Nombre','Contactos Email','Act/Desc','Edit','Del'], 
		colModel:[ {name:'formulariocod',index:'formulariocod', width:20,  align:"center",sortable:false}, 
				  {name:'formulariotipotitulo',index:'formulariotipotitulo', width:50,sortable:false},
				  {name:'email',index:'email',  align:"center",width:10, sortable:false},
				  {name:'act',index:'act',  align:"center",width:10, sortable:false},
				  {name:'edit',index:'edit', align:"center",width:10, sortable:false},
				  {name:'del',index:'del',  align:"center",width:10, sortable:false},
			  ], 
		rowNum:20, 
		ajaxGridOptions: {cache: false},
		rowList:[20,40,60],
		mtype: "POST",
		pager: '#pager2', 
		sortname: 'formulariocod', 
		viewrecords: true, 
		sortorder: "asc", 
		height:290,
		caption:"",
		emptyrecords: "Sin formularios para mostrar.",
		loadError : function(xhr,st,err) {
			  // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
				//alert("Error al procesar los datos");
		},
	
	}); 

	$(window).bind('resize', function() {
		$("#listarContactos").setGridWidth($("#LstContactos").width());
	}).trigger('resize');
		jQuery("#listarContactos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "con_contactos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadContactos ();
			alert(msg.Msg);	
		}
		 else
		{
			alert(msg.Msg);	 
		}
		 
	   }
	   
	 });
}



function EliminarFormContacto(formulariocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el formulario de contacto?"))
		return false;
	param = "formulariocod="+formulariocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(formulariocod,tipo)
{
	var param;
	param = "formulariocod="+formulariocod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function EmailDestino(formulariocod)
{
	var param, url;
	$("#cargando").show();
	param += "&formulariocod="+formulariocod;
	$.ajax({
	   type: "POST",
	   url: "con_contactos_email_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 420, 
				width: 600, 
				position: 'center', 
				modal:false,
				title: "Emails", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}	

