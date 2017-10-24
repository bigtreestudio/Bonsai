jQuery(document).ready(function(){
	listarMultimedia();	
	LoadButton("#multimediaidexterno");
});
	
function LoadButton(id)
{
	$(id).keyup(function(){$(id).blur(); $(id).focus();});
	$(id).change(function(){gridReload()});		 
}

var timeoutHndMultimedia; 
function doSearchMultimedia(ev){ 
	if(timeoutHndMultimedia) 
		clearTimeout(timeoutHndMultimedia) 
	timeoutHndMultimedia = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoMultimedia").jqGrid('setGridParam', {url:"mul_multimedia_lst_ajax.php?rand="+Math.random(), postData: datos}).trigger("reloadGrid"); 
} 

function gridReloadPaginaPrincipal(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoMultimedia").jqGrid('setGridParam', {url:"mul_multimedia_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


function Limpiar()
{
	$("#multimedianombre").val("");
	$("#multimediatipoarchivo").val("");
	$("#multimediaidexterno").val("");
	$("#catcod").val("");
	$("#multimediaestadocod").val("");
	gridReloadPaginaPrincipal();
}

function listarMultimedia()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoMultimedia").jqGrid(
	{ 

				url:'mul_multimedia_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Imagen','Nombre','Categoria','Tipo','Previsualizar','Editar','Estado','Eliminar'], 
				colModel:[ {name:'multimediacod',index:'multimediacod', width:10, align:"center"}, 
						  {name:'imagen',index:'imagen', width:15, align:"center", sortable:false},
						  {name:'multimedianombre',index:'multimedianombre'},
						  {name:'multimediaconjuntodesc',index:'multimediaconjuntodesc',align:"center",width:20},
						  {name:'multimediatipoarchivo',index:'multimediatipoarchivo',align:"center",width:20},
						  {name:'visualizar',index:'visualizar', width:15, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'multimediaestadocod',index:'multimediaestado',width:15,align:"center",sortable:false},						  
						  {name:'del',index:'del',width:15,align:"center",sortable:false},						  
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'multimediacod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:320,
				caption:"",
				emptyrecords: "Sin multimedias para mostrar.",
				loadError : function(xhr,st,err) {
                       //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                }			
				
			}); 
	
			$(window).bind('resize', function() {
				$("#ListadoMultimedia").setGridWidth($("#LstMultimedia").width());
			}).trigger('resize');
				jQuery("#ListadoMultimedia").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
	
}
	
function EnviarDatos(param)
{
	$.ajax({
		type: "POST",
		url: "mul_multimedia_upd.php",
		data: param,
		dataType:"json",
		success: function(msg){
			if (msg.IsSuccess==true)
			{
				alert(msg.Msg);
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


function EliminarMultimedia(multimediacod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el multimedia"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando multimedia...</h1>',baseZ: 99999999999});
	param = "multimediacod="+multimediacod;
	param += "&accion=1";
	EnviarDatos(param);

	return true;
}

	function ActivarDesactivar(multimediacod,tipo)
	{
		var param;
		$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
		param = "multimediacod="+multimediacod;
		param += "&accion="+tipo;
		EnviarDatos(param);
	}
		
function DialogClose()
{
	 $("#Popup").dialog("close"); 
}



function VisualizarMultimedia(codigo)
{
	$("#MsgGuardando").html("Cargando archivo multimedia...");
	$("#MsgGuardando").show();
	param = "multimediacod="+codigo;
	$.ajax({
	   type: "POST",
	   url: "mul_multimedia_visualizar.php",
	   data: param,
	   success: function(msg){
			$("#PopupVisualizarMultimedia").dialog({	
				width: 650, 
				zIndex: 999999999,
				position: 'top', 
				modal:true,
				title: "Multimedia", 
				open: function(type, data) {
						$("#PopupVisualizarMultimedia").html(msg);
						$("#MsgGuardando").hide();
						$("#MsgGuardando").html("Guardando...");
					},
				close: function(type, data) {
						$("#PopupVisualizarMultimedia").html("");
					}
			});
	   }
	 });
}
