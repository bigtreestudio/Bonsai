// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	listarTiposMenu();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposMenu").jqGrid('setGridParam', {url:"tap_menu_tipos_listado_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#menutipodesc").val("");
	$("#menutipoarchivo").val("");
	$("#menutipocte").val("");
	$("#menuclass").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}

function listarTiposMenu()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposMenu").jqGrid(
	{ 

				url:'tap_menu_tipos_listado_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Archivo','Cte','Class','Editar Menu','Del'], 
				colModel:[ {name:'menutipocod',index:'menutipocod', width:20, align:"center", hidden:true}, 
						  {name:'menutipodesc',index:'menutipodesc'},
						  {name:'menutipoarchivo',index:'menutipoarchivo'},
						  {name:'menutipocte',index:'menutipocte'},
						  {name:'menuclass',index:'menuclass'},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'menutipodesc', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin men\u00fa para mostrar.",
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
				$("#ListarTiposMenu").setGridWidth($("#LstTiposMenu").width());
			}).trigger('resize');
				jQuery("#ListarTiposMenu").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

function FormMenuTipo(modif,menutipocod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "menutipocod="+menutipocod;
	
	$.ajax({
	   type: "POST",
	   url: "tap_menu_tipos_listado_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 400, 
				width: 250,
				zIndex: 999999, 
				position: 'center', 
				modal:false,
				title: "Tipo Men\u00fa", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaMenuTipo()
{
	FormMenuTipo(0,0);
	return true;
}

function EditMenuTipo(menutipocod)
{
	FormMenuTipo(1,menutipocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_menu_tipos_listado_upd.php",
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
	param = $("#formmenutipo").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function Modificar()
{
	var param;
	param = $("#formmenutipo").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function EliminarMenuTipo(menutipocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el tipo de men\u00fa?"))
		return false;
	param = "menutipocod="+menutipocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}
function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

