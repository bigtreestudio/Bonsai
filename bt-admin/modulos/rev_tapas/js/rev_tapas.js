jQuery(document).ready(function(){
	listarRevTapa();	
});
	
	var timeoutHnd; 
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarRevTapa").jqGrid('setGridParam', {url:"rev_tapas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarRevTapa()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarRevTapa").jqGrid(
	{ 

				url:'rev_tapas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Tapa','Tipo', 'Nro','Fecha','Act/Desc','Edit','Del'], 
				colModel:[ {name:'revtapacod',index:'revtapacod', width:20, align:"center"}, 
						  {name:'revtapatitulo',index:'revtapatitulo'}, 
						  {name:'revtapatiponombre',index:'revtapatiponombre'}, 
						  {name:'revtapatitulo',index:'revtapanumero',width:10},
						  {name:'revtapafecha',index:'revtapafecha',width:40}, 
						  {name:'act',index:'act', width:25, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:25, align:"center", sortable:false},
						  {name:'del',index:'del', width:25, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'revtapacod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin Tapas para mostrar.",
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
				$("#listarRevTapa").setGridWidth($("#LstRevTapa").width());
			}).trigger('resize');
				jQuery("#listarRevTapa").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
				

}
	
function FormRevTapa(modif,revtapacod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	
	if (modif)
		param += "&revtapacod="+revtapacod;
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 280, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Areas", 
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
	   url: "rev_tapas_upd.php",
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

function EliminarRevTapa(revtapacod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la Tapa?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Eliminando tapa...</h1>',baseZ: 9999999999 })	
	param = "revtapacod="+revtapacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(revtapacod,tipo)
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="./images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "revtapacod="+revtapacod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}



