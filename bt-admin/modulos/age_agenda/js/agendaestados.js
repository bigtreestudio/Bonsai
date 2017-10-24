// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){  
	ListarAgendaEstados();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarAgendaEstados").jqGrid('setGridParam', {url:"age_agenda_estados_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#agendaestadodesc").val("");
	$("#agendaestadocte").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}

function ListarAgendaEstados()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarAgendaEstados").jqGrid(
	{ 

				url:'age_agenda_estados_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Constante','color','Act/Desac','Editar','Del'], 
				colModel:[ {name:'agendaestadocod',index:'agendaestadocod', width:20, align:"center", hidden:true}, 
						  {name:'agendaestadodesc',index:'agendaestadodesc'},
						  {name:'agendaestadocte',index:'agendaestadocte'},
						  {name:'color',index:'color', width:20, align:"center", sortable:false},
						  {name:'act',index:'act', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'agendaestadocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin tipos de metadata campos para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                       alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarAgendaEstados").setGridWidth($("#LstAgendaEstados").width());
			}).trigger('resize');
				jQuery("#ListarAgendaEstados").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

function FormAgendaEstados(modif,agendaestadocod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "agendaestadocod="+agendaestadocod;
	
	$.ajax({
	   type: "POST",
	   url: "age_agenda_estados_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 370, 
				width: 330,
				zIndex: 99, 
				position: 'center', 
				modal:false,
				title: "Agenda Estado", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaAgendaEstados()
{
	FormAgendaEstados(0,0);
	return true;
}

function EditarAgendaEstados(agendaestadocod)
{
	FormAgendaEstados(1,agendaestadocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "age_agenda_estados_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert(msg.Msg);
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

function Insertar()
{
	var param;
	param = $("#formagendaestado").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function Modificar()
{
	var param;
	param = $("#formagendaestado").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function EliminarAgendaEstados(agendaestadocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el estado de la agenda?"))
		return false;
	param = "agendaestadocod="+agendaestadocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(agendaestadocod,tipo)
{
	
	var param;
	param = "agendaestadocod="+agendaestadocod;
	param += "&accion="+tipo;
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

