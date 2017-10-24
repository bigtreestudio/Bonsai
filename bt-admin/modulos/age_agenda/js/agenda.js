jQuery(document).ready(function(){
	listarEvento();	
});
	
	var timeoutHnd; 
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarEvento").jqGrid('setGridParam', {url:"age_agenda_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarEvento()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarEvento").jqGrid(
	{ 

				url:'age_agenda_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','T&iacute;tulo del Evento','Fecha Inicio/Fin','Hora Inicio/Fin','Act/Desc','Edit','Del'], 
				colModel:[ {name:'agendacod',index:'agendacod', width:20, align:"center"}, 
						  {name:'agendatitulo',index:'agendatitulo'}, 
						  {name:'fecha',index:'fecha', width:40, align:"center", sortable:false},	
						  {name:'hora',index:'hora', width:40, align:"center", sortable:false},	
						  {name:'act',index:'act', width:15, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:25, align:"center", sortable:false},
						  {name:'del',index:'del', width:25, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'agendacod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin Eventos para mostrar.",
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
				$("#listarEvento").setGridWidth($("#LstEvento").width());
			}).trigger('resize');
				jQuery("#listarEvento").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
				
}

	
function FormEvento(modif,agendacod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	
	if (modif)
		param += "&agendacod="+agendacod;
	$.ajax({
	   type: "POST",
	   url: "age_agenda_alta.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 280, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Evento", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "age_agenda_alta_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
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

function EliminarEvento(agendacod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el Evento?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando evento...</h1>',baseZ: 9999999999 })	
	param = "agendacod="+agendacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}


function ActivarDesactivar(agendacod,tipo)
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "agendacod="+agendacod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}



