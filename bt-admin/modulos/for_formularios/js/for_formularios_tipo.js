jQuery(document).ready(function(){
	listarFormulariosTipo();	
});
	
	var timeoutHnd; 
	
function doSearchFormFormularios(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadFormularios,500) 
}

function gridReloadFormularios(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTiposFormularios").jqGrid('setGridParam', {url:"for_formularios_tipo_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarFormulariosTipo()
	{
	
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTiposFormularios").jqGrid(
	{ 
				url:'for_formularios_tipo_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Tipo','Editar','Eliminar'], 
				colModel:[ {name:'formulariotipocod',index:'formulariotipocod', width:20,  align:"center",sortable:false}, 
						  {name:'formulariodesc',index:'formulariodesc', width:50,sortable:false},
						  {name:'edit',index:'edit',  align:"center",width:10, sortable:false},
						  {name:'del',index:'del',  align:"center",width:10, sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'formulariotipocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin datos para mostrar.",
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
				$("#listarTiposFormularios").setGridWidth($("#LstTiposFormularios").width());
			}).trigger('resize');
				jQuery("#listarTiposFormularios").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function FormFormularios(formulariocod)
{
	
	$.ajax({
	   type: "POST",
	   url: "for_formularios_tipo_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadFormularios ();
			//alert(msg.Msg);
			//$("#Popup").dialog("close"); 
			//alert(msg.Msg);	
		}
		 else
		{
			alert(msg.Msg);	 
			$.unblockUI();	
		}
		 
	   }
	   
	 });
}


function FormFormularioEditar(formulariotipocod)
{
	FormFormularios(1,formulariotipocod);
	return true;
}
function FormFormularioInsertar()
{
	FormFormularios(0);
	return true;
}

function FormFormularios(modif,formulariotipocod)
{
	var param, url;
	$("#cargando").show();
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Cargando...</h1>',baseZ: 9999999999 })	
	param = "";
	if (modif)
		param += "&formulariotipocod="+formulariotipocod;
	$.ajax({
	   type: "POST",
	   url: "for_formularios_tipo_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 380, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Formularios", 
				open: function(type, data) {$("#Popup").html(msg);$(".chzn-select").chosen();$.unblockUI();	}
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
	   url: "for_formularios_tipo_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadFormularios ();
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



function EliminarFormFormulariosTipo(formulariotipocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el formulario?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Eliminando...</h1>',baseZ: 9999999999 })	
	param = "formulariotipocod="+formulariotipocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}


function DialogClose()
{

	 $("#Popup").dialog("close"); 
}

function InsertarFormulariosTipos()
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Agregando...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarFormulariosTipos()
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" /> Actualizando...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function CargarMenu()
{
	var param="tipo=4&menutipocod="+$("#menutipocod").val();
	$("#Menus").html("Cargando menu...");	
	$.ajax({
	   type: "POST",
	   url: "combo_ajax.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#Menus").html(msg);	 
			$(".chzn-select").chosen();
	   }
	   
	 });
}

