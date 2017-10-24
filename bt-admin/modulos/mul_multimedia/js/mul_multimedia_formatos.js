jQuery(document).ready(function(){
	ListarMultFormatos();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMultFormatos").jqGrid('setGridParam', {url:"mul_multimedia_formatos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#formatodescbusqueda").val("");
	$("#formatoanchobusqueda").val("");
	$("#formatoaltobusqueda").val("");
	$("#formatocarpetabusqueda").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarMultFormatos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarMultFormatos").jqGrid(
	{ 
				url:'mul_multimedia_formatos_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Ancho','Alto','Formato Carpeta','Act/Desc','Editar','Eliminar'], 
				colModel:[ {name:'formatocod',index:'formatocod', width:20, align:"center", hidden:true}, 
						  {name:'formatodesc',index:'formatodesc'}, 
  						  {name:'formatoancho',index:'formatoancho'}, 
						  {name:'formatoalto',index:'formatoalto'},
						  {name:'formatocarpeta',index:'formatocarpeta'},
						  {name:'act/desc',index:'act/desc', width:40, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'formatodesc', 
				viewrecords: true, 
				sortorder: "desc", 
				height:440,
				caption:"",
				emptyrecords: "Sin formatos para mostrar.",
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
				$("#ListarMultFormatos").setGridWidth($("#LstMultFormatos").width());
			}).trigger('resize');
				jQuery("#ListarMultFormatos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}


function FormMultFormatos(modif,formatocod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "formatocod="+formatocod;
	
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_formatos_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 470, 
				width: 340,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Formato", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaMultFormatos()
{
	FormMultFormatos(0,0);
	return true;
}

function EditMultFormatos(formatocod)
{
	FormMultFormatos(1,formatocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_formatos_upd.php",
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

function InsertarMultFormatos()
{
	var param;
	param = $("#formmultformato").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function ModificarMultFormatos()
{
	var param;
	param = $("#formmultformato").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function EliminarMulFormato(formatocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el formato?"))
		return false;
	param = "formatocod="+formatocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(formatocod,tipo)
{
	
	var param;
	param = "formatocod="+formatocod;
	param += "&accion="+tipo;
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}