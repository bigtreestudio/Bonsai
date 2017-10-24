jQuery(document).ready(function(){
	listarFormularios();	
});
	
	var timeoutHnd; 
	
function doSearchFormFormularios(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadFormularios,500) 
}

function gridReloadFormularios(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarFormularios").jqGrid('setGridParam', {url:"for_formularios_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarFormularios()
	{
	
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarFormularios").jqGrid(
	{ 
				url:'for_formularios_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Mensaje','Ver'], 
				colModel:[ {name:'formulariodatoscod',index:'formulariodatoscod', width:20,  align:"center",sortable:false}, 
						  {name:'formularionombre',index:'formularionombre', width:50,sortable:false},
						  {name:'formulariocomentario',index:'formulariocomentario', width:50,sortable:false},
						  {name:'ver',index:'ver',  align:"center",width:10, sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'formulariodatoscod', 
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
				$("#listarFormularios").setGridWidth($("#LstFormulario").width());
			}).trigger('resize');
				jQuery("#listarFormularios").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
	
function FormFormularios(formulariocod)
{
	
	$.ajax({
	   type: "POST",
	   url: "for_formularios_upd.php",
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


function EditarFormContacto(formulariocod)
{
	FormContacto(1,formulariocod);
	return true;
}
	

	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "for_formularios_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadContactos ();
			//alert(msg.Msg);
			//$("#Popup").dialog("close"); 
			alert(msg.Msg);	
		}
		 else
		{
			alert(msg.Msg);	 
			$.unblockUI();	
		}
		 
	   }
	   
	 });
}



function EliminarFormFormularios(formulariodatoscod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el mensaje recibido?"))
		return false;
	param = "formulariodatoscod="+formulariodatoscod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(formulariodatoscod,tipo)
{
	var param;
	param = "formulariodatoscod="+formulariodatoscod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{

	 $("#Popup").dialog("close"); 
}

function InsertarFormFormularios()
{
	var param;
	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarFormFormularios(formulariodatoscod)
{

	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function FormFormularioVer(formulariodatoscod)
{
	var param, url;
	$("#cargando").show();
	param = "formulariodatoscod="+formulariodatoscod;
		
		$.ajax({
	   type: "POST",
	   url: "for_formularios_ver.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 460, 
				width: 450,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Mensaje", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
		   }
		 
		 }
		 
		 );

	return true;
}