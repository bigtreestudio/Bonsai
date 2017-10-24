jQuery(document).ready(function(){
	ListarDepartamentos();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarDepartamentos").jqGrid('setGridParam', {url:"ciudades_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#feriadosmes").val("");
	$("#feriadosano").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarDepartamentos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarDepartamentos").jqGrid(
	{ 
				url:'ciudades_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Act/Desc','Editar','Eliminar'], 
				colModel:[ {name:'departamentocod',index:'departamentocod', width:20, align:"center", hidden:true}, 
						  {name:'departamentodesc',index:'departamentodesc'},
						  {name:'act/desc',index:'act/desc', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'departamentodesc', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin ciudades para mostrar.",
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
				$("#ListarDepartamentos").setGridWidth($("#LstDepartamentos").width());
			}).trigger('resize');
				jQuery("#ListarDepartamentos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

function FormDepartamento(modif,provinciacod,departamentocod)
{
	var param, url;
	$("#cargando").show();
	param = "provinciacod="+provinciacod;
	if (modif)
		param += "=&departamentocod="+departamentocod;
	
	$.ajax({
	   type: "POST",
	   url: "ciudades_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 200, 
				width: 550,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Ciudades", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
		   }
		 
		 }
		 
		 );
	
		return true;
}



	function AltaDepartamento(provinciacod)
	{
		FormDepartamento(0,provinciacod);
		return true;
	}
	function EditarDepartamento(provinciacod,departamentocod)
	{
		FormDepartamento(1,provinciacod,departamentocod);
		return true;
	}
	
	
		
function EnviarDatos(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "ciudades_upd.php",
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

	
function InsertarDepartamento()
{
	var param;
	param = $("#formciudades").serialize();
	param += "&accion=1";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}


function ModificarDepartamento()
{
	var param;
	param = $("#formciudades").serialize();
	param += "&accion=2";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}


function EliminarDepartamento(provinciacod,departamentocod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la ciudad?"))
		return false;
	
	param = "provinciacod="+provinciacod;
	param += "&departamentocod="+departamentocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(provinciacod,departamentocod,tipo)
{
	var param;
	param = "provinciacod="+provinciacod;
	param += "&departamentocod="+departamentocod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}
	
function DialogClose()
{
	 $("#Popup").dialog("close"); 
}


