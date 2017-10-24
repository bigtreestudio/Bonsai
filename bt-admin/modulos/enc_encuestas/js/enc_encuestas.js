jQuery(document).ready(function(){
	listarEncuestas();	
});
	
	var timeoutHnd; 
	
function doSearchEncuestas(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadEncuestas,500) 
}

function gridReloadEncuestas(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarEncuestas").jqGrid('setGridParam', {url:"enc_encuestas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarEncuestas()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarEncuestas").jqGrid(
	{ 

				url:'enc_encuestas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Respuestas','Opciones','Act/Desc','Edit','Del'], 
				colModel:[ {name:'encuestacod',index:'encuestacod', width:20, align:"center",sortable:false}, 
						  {name:'encuestapregunta',index:'encuestapregunta',sortable:false},
						  {name:'encuestasrespuestas',index:'encuestasrespuestas', align:"center",width:20,sortable:false}, 							
						  {name:'encuestaopciones',index:'encuestaopciones', align:"center",width:20,sortable:false}, 							
						  {name:'act',index:'act', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'encuestacod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin encuestas para mostrar.",
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
				$("#listarEncuestas").setGridWidth($("#LstEncuestas").width());
			}).trigger('resize');
				jQuery("#listarEncuestas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function FormEncuestas(modif,encuestacod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param += "&encuestacod="+encuestacod;
	$.ajax({
	   type: "POST",
	   url: "enc_encuestas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 280, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Encuestas", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}

function AltaEncuestas()
{
	FormEncuestas(0,'');
	return true;
}
	
function EditarEncuestas(encuestacod)
{
	FormEncuestas(1,encuestacod);
	return true;
}
	
function EncuestaOpciones(encuestacod)
{
	var param, url;
	$("#cargando").show();
	param += "&encuestacod="+encuestacod;
	$.ajax({
	   type: "POST",
	   url: "enc_encuestas_opciones_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 410, 
				width: 600, 
				position: 'center', 
				modal:false,
				title: "Opciones", 
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
	   url: "enc_encuestas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadEncuestas();
			//alert(msg.Msg);
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

function EliminarEncuesta(encuestacod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la Encuesta?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando encuesta...</h1>',baseZ: 9999999999 })	
	param = "encuestacod="+encuestacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(encuestacod,tipo)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Procesando...</h1>',baseZ: 9999999999 })	
	var param;
	param = "encuestacod="+encuestacod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{

	 $("#Popup").dialog("close"); 
}

function InsertarEncuestas()
{
	var param;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Agregando encuesta...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarEncuestas(encuestacod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Modificando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}