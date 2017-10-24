jQuery(document).ready(function(){
	listarTapasModulos();	
});
	
	var timeoutHnd; 
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTapasModulos").jqGrid('setGridParam', {url:"tap_modulos_editar_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarTapasModulos()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarTapasModulos").jqGrid(
	{ 

				url:'tap_modulos_editar_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Descripcion','Del'], 
				colModel:[ {name:'modulocod',width:100,index:'modulocod', align:"center"}, 
						  {name:'modulodesc',width:300,index:'modulodesc'}, 	
						  {name:'modulodata',width:600,index:'modulodata'},
						  {name:'del',index:'del', width:100, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'modulocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin modulos para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
			
			}); 
	
}
	

	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "tap_modulos_editar_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
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

function EliminarTapaModulo(zonamodulocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el Modulo de la Tapa?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Eliminando tapa...</h1>',baseZ: 9999999999 })	
	param = "zonamodulocod="+zonamodulocod;
	param += "&accion=1";
	EnviarDatos(param);

	return true;
}


function DialogClose()
{
	 $("#Popup").dialog("close"); 
}





