jQuery(document).ready(function(){
	ListarPlantillasMacros();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPlantillasMacros").jqGrid('setGridParam', {url:"tap_macros_estructuras_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#formatodescbusqueda").val("");
	$("#formatoanchobusqueda").val("");
	$("#formatoaltobusqueda").val("");
	$("#formatocarpetabusqueda").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarMacroColumnas(orden,macrocod)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&macrocod="+macrocod;
	param += "&accion=4";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_macros_estructuras_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSuccess)
			{
				
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function ListarPlantillasMacros()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPlantillasMacros").jqGrid(
	{ 
				url:'tap_macros_estructuras_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Class','Columnas','Editar','Eliminar'], 
				colModel:[ {name:'estructuracod',index:'estructuracod', width:20, align:"center", hidden:true}, 
  						  {name:'estructuradesc',index:'estructuradesc',sortable:false}, 
						  {name:'estructuraclass',index:'estructuraclass', width:60, align:"center", sortable:false},
						  {name:'col',index:'col', width:30, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'estructuraorden', 
				viewrecords: true, 
				sortorder: "ASC", 
				height:440,
				caption:"",
				emptyrecords: "Sin estructuras para mostrar.",
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
				$("#ListarPlantillasMacros").setGridWidth($("#LstPlantillasMacros").width());
			}).trigger('resize');
				jQuery("#ListarPlantillasMacros").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			jQuery("#ListarPlantillasMacros").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarPlantillasMacros").jqGrid("getDataIDs");
				   macrocod= $("#macrocod").val();
				   ReordenarMacroColumnas(neworder,macrocod);
			   }}
			 );	
}

function FormMacrosEstructuras(modif,estructuracod,macrocod)
{
	var param, url;
	$("#cargando").show();
	param = "macrocod="+macrocod;
	if (modif)
		param += "&estructuracod="+estructuracod;
	$.ajax({
	   type: "POST",
	   url: "tap_macros_estructuras_am.php",
	   data: param,
	   success: function(msg){
			$("#ModalMacroCol").dialog({	
				height: 280, 
				width: 550,
				zIndex: 999999999,  
				position: 'center', 
				title: "Macros", 
				open: function(type, data) {$("#ModalMacroCol").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function AltaMacroEstructura(macrocod)
{
	FormMacrosEstructuras(0,0,macrocod);
	return true;
}
function EditMacrostructura(estructuracod,macrocod)
{
	FormMacrosEstructuras(1,estructuracod,macrocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_macros_estructuras_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert(msg.Msg);
			DialogClose(); 
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

function InsertarMacroEstructura()
{
	
	var param;
	param = $("#formmacroestructura").serialize();
	param += "&accion=1";
	//alert(param);
	EnviarDatos(param);
	
	return true;
}

function ModificarMacroEstructura()
{
	
	var param;
	param = $("#formmacroestructura").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarMacroEstructura(estructuracod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la estructura?"))
		return false;
	param = "estructuracod="+estructuracod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#ModalMacroCol").dialog("close"); 
}

