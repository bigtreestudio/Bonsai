// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){  
	ListarTiposMetadataCampos();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposMetadataCampos").jqGrid('setGridParam', {url:"tap_tapas_tipos_metadata_campos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#tapatipometadatacampo").val("");
	$("#tapatipometadatacte").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}

function ListarTiposMetadataCampos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposMetadataCampos").jqGrid(
	{ 

				url:'tap_tapas_tipos_metadata_campos_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Constante','Act/Desac','Editar','Del'], 
				colModel:[ {name:'tapatipometadatacod',index:'tapatipometadatacod', width:20, align:"center", hidden:true}, 
						  {name:'tapatipometadatacampo',index:'tapatipometadatacampo'},
						  {name:'tapatipometadatacte',index:'tapatipometadatacte'},
						  {name:'act',index:'act', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'tapatipometadatacampo', 
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
				$("#ListarTiposMetadataCampos").setGridWidth($("#LstTiposMetadataCampos").width());
			}).trigger('resize');
				jQuery("#ListarTiposMetadataCampos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

function FormMenuTipo(modif,tapatipometadatacod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "tapatipometadatacod="+tapatipometadatacod;
	
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_tipos_metadata_campos_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 280, 
				width: 400,
				zIndex: 999999, 
				position: 'center', 
				modal:false,
				title: "Tipo Metadato Campo", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaTipoMetadataCampo()
{
	FormMenuTipo(0,0);
	return true;
}

function EditTipoMetadataCampo(tapatipometadatacod)
{
	FormMenuTipo(1,tapatipometadatacod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_tipos_metadata_campos_upd.php",
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
	param = $("#formtipometadatacampo").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function Modificar()
{
	var param;
	param = $("#formtipometadatacampo").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function EliminarTipoMetadataCampo(tapatipometadatacod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el tipo de metadata campo?"))
		return false;
	param = "tapatipometadatacod="+tapatipometadatacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(tapatipometadatacod,tipo)
{
	
	var param;
	param = "tapatipometadatacod="+tapatipometadatacod;
	param += "&accion="+tipo;
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

