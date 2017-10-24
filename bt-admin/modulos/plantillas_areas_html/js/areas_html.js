// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){  
	ListarAreasHTML();	
});
	

function gridReload(){
	jQuery("#ListadoAreasHtml").jqGrid('setGridParam', {url:"tap_plantillas_areas_html_lst_ajax.php?rand="+Math.random(), page:1}).trigger("reloadGrid"); 
} 


	
function ListarAreasHTML()
{
	jQuery("#ListadoAreasHtml").jqGrid(
	{ 
				url:'tap_plantillas_areas_html_lst_ajax.php?rand='+Math.random(),
				datatype: "json", 
				colNames:['COD','Nombre','Editar','Eliminar'], 
				colModel:[ {name:'areahtmlcod',index:'areahtmlcod', width:20, align:"center", hidden:true}, 
						  {name:'areahtmldesc',index:'areahtmldesc', sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				sortname: 'areahtmlcod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin areas html para mostrar.",
				loadError : function(xhr,st,err) {
                       alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListadoAreasHtml").setGridWidth($("#LstAreasHtml").width());
			}).trigger('resize');
				jQuery("#ListadoAreasHtml").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

function FormArea(modif,areahtmlcod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "areahtmlcod="+areahtmlcod;
	
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_areas_html_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 710, 
				width: 750,
				zIndex: 999999999,  
				position: 'center', 
				modal:false,
				title: "Area HTML", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaAreaHTML()
{
	FormArea(0,0);
	return true;
}

function EditarAreaHTML(areahtmlcod)
{
	FormArea(1,areahtmlcod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_areas_html_upd.php",
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
	param = $("#formareahtml").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function Modificar()
{
	var param;
	param = $("#formareahtml").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function Eliminar(areahtmlcod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el html?"))
		return false;
	param = "areahtmlcod="+areahtmlcod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}


function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

