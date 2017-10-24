jQuery(document).ready(function(){
	ListarTiposDocumentos();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposDocumentos").jqGrid('setGridParam', {url:"tipos_documentos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarTiposDocumentos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTiposDocumentos").jqGrid(
	{ 
				url:'tipos_documentos_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Act/Desc','Editar','Eliminar'], 
				colModel:[ {name:'tipodocumentocod',index:'tipodocumentocod', width:20, align:"center", hidden:true}, 
						  {name:'tipodocumentonombre',index:'tipodocumentonombre'},
						  {name:'act/desc',index:'act/desc', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'tipodocumentonombre', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin tipos de documentos para mostrar.",
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
				$("#ListarTiposDocumentos").setGridWidth($("#LstTiposDocumentos").width());
			}).trigger('resize');
				jQuery("#ListarTiposDocumentos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}



function FormTipodocumento(modif,tipodocumentocod)
{
	var param, url;
	$("#cargando").show();
	if (modif)
		param = "tipodocumentocod="+tipodocumentocod;
	
	$.ajax({
   type: "POST",
   url: "tipos_documentos_am.php",
   data: param,
   success: function(msg){
		$("#Popup").dialog({	
			height: 200, 
			width: 550,
			zIndex: 999999999, 
			position: 'center', 
			modal:false,
			title: "Tipo de Documento", 
			open: function(type, data) {$("#Popup").html(msg); }
		});
		$("#cargando").hide();
   
	   }
	 
	 }
	 
	 );

	return true;
}

function AltaTipoDocumento()
{
	FormTipodocumento(0,0);
	return true;
}
function EditarTipoDocumento(tipodocumentocod)
{
	FormTipodocumento(2,tipodocumentocod);
	return true;
}





function EnviarDatos(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tipos_documentos_upd.php",
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
	param = $("#formtiposdocumentos").serialize();
	param += "&accion=1";
	
	EnviarDatos(param);
	
	return true;
}


function Modificar()
{
	var param;
	param = $("#formtiposdocumentos").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}


function EliminarTipoDocumento(tipodocumentocod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el tipo de documento?"))
		return false;
	param = "tipodocumentocod="+tipodocumentocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(tipodocumentocod,tipo)
{
	var param;
	param = "tipodocumentocod="+tipodocumentocod;
	param += "&accion="+tipo;
	EnviarDatos(param);
	
}


function DialogClose()
{
	 $("#Popup").dialog("close"); 
}
