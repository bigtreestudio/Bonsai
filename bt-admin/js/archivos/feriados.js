jQuery(document).ready(function(){
	ListarFeriados();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarFeriados").jqGrid('setGridParam', {url:"feriados_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#feriadosmes").val("");
	$("#feriadosano").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarFeriados()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarFeriados").jqGrid(
	{ 
				url:'feriados_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Fecha','Act/Desc','Editar','Eliminar'], 
				colModel:[ {name:'feriadocod',index:'feriadocod', width:20, align:"center", hidden:true}, 
						  {name:'feriadodesc',index:'feriadodesc'},
						  {name:'feriadodia',index:'feriadodia',align:"center", width:15}, 
						  {name:'act/desc',index:'act/desc', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'feriadodia', 
				viewrecords: true, 
				sortorder: "asc", 
				height:640,
				caption:"",
				emptyrecords: "Sin feriados para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                       //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarFeriados").setGridWidth($("#LstFeriados").width());
			}).trigger('resize');
				jQuery("#ListarFeriados").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}


function FormFeriados(modif,feriadocod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "feriadocod="+feriadocod;
	
	$.ajax({
	   type: "POST",
	   url: "feriados_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 250, 
				width: 550,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Feriados", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaFeriados()
{
	FormFeriados(0,0);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "feriados_upd.php",
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

function InsertarFeriados()
{
	
	var param;
	param = $("#formferiados").serialize();
	param += "&accion=1";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}

function EditarFeriados(feriadocod)
{
	
	FormFeriados(1,feriadocod);
	return true;
}


function ModificarFeriados()
{
	
	var param;
	param = $("#formferiados").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function ActivarDesactivar(feriadocod,tipo)
{
	
	var param;
	param = "feriadocod="+feriadocod;
	param += "&accion="+tipo;
	EnviarDatos(param);

	return true;
}

function EliminarFeriados(feriadocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el feriado?"))
		return false;
	param = "feriadocod="+feriadocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}
