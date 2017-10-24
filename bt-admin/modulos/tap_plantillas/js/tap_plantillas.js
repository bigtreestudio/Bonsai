jQuery(document).ready(function(){
	ListarPlantillas();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPlantillas").jqGrid('setGridParam', {url:"tap_plantillas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#plantdesc").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarPlantillas()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPlantillas").jqGrid(
	{ 
				url:'tap_plantillas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripcion','Confeccionar','Confeccionar','Editar','Eliminar'], 
				colModel:[ {name:'plantcod',index:'plantcod', width:20, align:"center", hidden:true}, 
  						  {name:'plantdesc',index:'plantdesc',sortable:false}, 
						  {name:'areas',index:'areas', width:30, align:"center", sortable:false},
						  {name:'link',index:'link', width:30, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'plantcod', 
				viewrecords: true, 
				sortorder: "ASC", 
				height:440,
				caption:"",
				emptyrecords: "Sin plantillas para mostrar.",
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
				$("#ListarPlantillas").setGridWidth($("#LstPlantillas").width());
			}).trigger('resize');
				jQuery("#ListarPlantillas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

}





function FormPlantilla(modif,plantcod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Cargando formulario...</h1>',baseZ: 9999999999 })	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param += "&plantcod="+plantcod;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 260, 
				width: 550,
				zIndex: 999999999,  
				position: 'center', 
				title: "Plantilla", 
				open: function(type, data) {$("#Popup").html(msg);$.unblockUI();}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function AltaPlantilla(plantcod)
{
	FormPlantilla(0,0);
	return true;
}
function EditarPlantilla(plantcod)
{
	FormPlantilla(1,plantcod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert(msg.Msg);
			DialogClosePlantilla();
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

function InsertarPlantilla()
{
	
	var param;
	param = $("#formplantilla").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function ModificarPlantilla()
{
	
	var param;
	param = $("#formplantilla").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarPlantilla(plantcod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la plantilla?"))
		return false;
	param = "plantcod="+plantcod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}
function DialogClosePlantilla()
{
	$("#Popup").dialog("close"); 
}



function AreasPlantilla(plantcod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Cargando formulario...</h1>',baseZ: 9999999999 })	
	var param, url;
	$("#cargando").show();
	param = "plantcod="+plantcod;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_areas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 500, 
				width: 650,
				zIndex: 999999999,  
				position: 'center', 
				title: "Areas de la plantilla", 
				open: function(type, data) {$("#Popup").html(msg);$.unblockUI();}
			});
			$("#cargando").hide();
	   }
	 });
	return false;
}
