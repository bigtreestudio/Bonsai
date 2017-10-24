jQuery(document).ready(function(){
	ListarProvincias();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarProvincias").jqGrid('setGridParam', {url:"provincias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarProvincias()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarProvincias").jqGrid(
	{ 

				url:'provincias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Activar / Desactivar ','Editar','Eliminar'], 
				colModel:[ {name:'provinciacod',index:'provinciacod', width:20, align:"center", hidden:true}, 
						  {name:'provinciadesc',index:'provinciadesc', sortable:false},
						  {name:'provinciaestado',index:'provinciaestado',align:"center", width:20, sortable:false}, 
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'provinciadesc', 
				viewrecords: true, 
				sortorder: 'asc', 
				height:440,
				caption:"",
				emptyrecords: "Sin provincias para mostrar.",
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
				$("#ListarProvincias").setGridWidth($("#LstProvincias").width());
			}).trigger('resize');
				jQuery("#ListarProvincias").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
}


function FormProvincia(modif,provinciacod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "provinciacod="+provinciacod;
		
		$.ajax({
	   type: "POST",
	   url: "provincias_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 200, 
				width: 550,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Provincias", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
		   }
		 
		 }
		 
		 );

	return true;
}

	function AltaProvincia()
	{
		FormProvincia(0,0);
		return true;
	}
	function EditarProvincia(provinciacod)
	{
		FormProvincia(1,provinciacod);
		return true;
	}
	
	
function EnviarDatos(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "provincias_upd.php",
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
	
	function InsertarProvincia()
	{
		var param;
		param = "provinciadesc="+$("#provinciadesc").val();
		param += "&accion=1";
		EnviarDatos(param);
		
		return true;
	}


	function ModificarProvincia()
	{
		var param;
		param = "provinciacod="+$("#provinciacod").val();
		param += "&provinciadesc="+$("#provinciadesc").val();
		param += "&accion=2";
		EnviarDatos(param);
		
		return true;
	}


	function EliminarProvincia(provinciacod)
	{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar la provincia?"))
			return false;
		param = "provinciacod="+provinciacod;
		param += "&accion=3";
		EnviarDatos(param);
	
		return true;
	}

	function ActivarDesactivar(provinciacod,tipo)
	{
		var param;
		param = "provinciacod="+provinciacod;
		param += "&accion="+tipo;
		EnviarDatos(param);
	}


	function DialogClose()
	{
		 $("#Popup").dialog("close"); 
	}
