jQuery(document).ready(function(){
	ListarGraficos();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	jQuery("#ListarGraficos").jqGrid('setGridParam', {url:"gra_graficos_lst_ajax.php?rand="+Math.random(),page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarGraficos()
{
	jQuery("#ListarGraficos").jqGrid(
	{ 

				url:'gra_graficos_lst_ajax.php?rand='+Math.random(),
				datatype: "json", 
				colNames:['COD','Titulo','Tipo','Estado','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'graficotitulo',index:'graficotitulo', sortable:false},
						  {name:'conjuntodesc',index:'conjuntodesc',align:"center", width:15, sortable:false}, 
						  {name:'graficoestado',index:'graficoestado',align:"center", width:15, sortable:false}, 
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'albumorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:300,
				caption:"",
				emptyrecords: "Sin graficos para mostrar.",
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
				$("#ListarGraficos").setGridWidth($("#LstGraficos").width());
			}).trigger('resize');
				jQuery("#ListarGraficos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}




function AltaGrafico(conjuntocod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando formulario...</h1>',baseZ: 9999999999 })	
	var param, url;
	param = "conjuntocod="+conjuntocod;
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_alta.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 300, 
				width: 550, 
				zIndex: 30000,
				position: 'center', 
				title: "Grafico nuevo", 
				open: function(type, data) {$("#Popup").html(msg); $.unblockUI();}
			});
	   }
	 });

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}


function EnviarDatosGrafico(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	//$("#Popup").dialog("close"); 
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			$("#Popup").dialog("close"); 
			alert("Se ha agregado el grafico correctamente");
			document.location.href=msg.archivo+"?graficocod="+msg.graficocod+"&md5="+msg.md5;
			$.unblockUI();	
		}
		 else
		{
			alert(msg.Msg);	 
			//$("#Popup").dialog("open");
			$.unblockUI();	
		}
	   }
	 });
}


function AgregarGrafico()
{
	param = $("#formulariografico").serialize();
	EnviarDatosGrafico(param);
	
	return true;
}



function EliminarGrafico(graficocod)
{
	if (!confirm("Esta seguro que desea eliminar el grafico completo?"))
		return false;
		
	param = "graficocod="+graficocod;
	param += "&accion=3";
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Eliminando grafico...</h1>',baseZ: 9999999999 })	
	$("#Popup").dialog("close"); 
	$.ajax({
	   type: "POST",
	   url: "gra_graficos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			alert("Se ha eliminado el grafico correctamente");
			$.unblockUI();	
		}
		 else
		{
			alert(msg.Msg);	 
			$.unblockUI();	
		}
	   }
	 });
	
	return true;
}


