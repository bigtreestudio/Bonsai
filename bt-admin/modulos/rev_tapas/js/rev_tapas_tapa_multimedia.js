jQuery(document).ready(function(){
	ListarRevTapaMultimedia();			


});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formrevtapamultimedia").serializeObject();
	jQuery("#ListarRevTapaMultimedia").jqGrid('setGridParam', {url:"rev_tapas_tapa_multimedia_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarRevTapaMultimedia()
{
	var datos = $("#formrevtapamultimedia").serializeObject();
	jQuery("#ListarRevTapaMultimedia").jqGrid(
	{ 
				url:'rev_tapas_tapa_multimedia_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Tapa','Orden','Act/Desc','Eliminar'], 
				colModel:[ {name:'revtapamulcod',index:'revtapamulcod', width:20, align:"center", sortable:false}, 
						  {name:'revtapamulubic',index:'revtapamulubic', align:"center", sortable:false}, 
						  {name:'orden',index:'orden', width:40, align:"center", sortable:false},
						  {name:'act/desc',index:'act/desc', width:40, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:5000, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'revtapamulorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin tapas para mostrar.",
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
				$("#ListarRevTapaMultimedia").setGridWidth($("#LstRevTapaMultimedia").width());
			}).trigger('resize');
				jQuery("#ListarRevTapaMultimedia").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});

			jQuery("#ListarRevTapaMultimedia").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarRevTapaMultimedia").jqGrid("getDataIDs");
				   ReordenarRevTapaMultimedia(neworder);
			   }}
			 )
}

function EnviarDatosMult(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_tapa_multimedia_upd.php",
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

function EliminarRevTapaMultimedia(revtapamulcod,revtapacod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la tapa?"))
		return false;
	param = "revtapamulcod="+revtapamulcod;
	param += "&revtapacod="+revtapacod;
	param += "&accion=3";
	EnviarDatosMult(param);

	return true;
}

function ActivarDesactivar(revtapamulcod,revtapacod,tipo)
{
	
	var param;
	param = "revtapamulcod="+revtapamulcod;
	param += "&revtapacod="+revtapacod;
	param += "&accion="+tipo;
	EnviarDatosMult(param);

	return true;
}

function FormRevTapaMultimedia(modif,revtapacod,revtapamulcod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando datos...</h1>',baseZ: 99999999999});
	var param, url;
	$("#cargando").show();
	param = "revtapacod="+revtapacod;
	if (modif)
		param += "&revtapamulcod="+revtapamulcod;
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_tapa_multimedia_alta.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 300, 
				zIndex: 9999,
				width: 700, 
				position: 'center', 
				modal:true,
				title: "TAPAS", 
				open: function(type, data) {$("#Popup").html(msg); initTextEditors(); $.unblockUI();}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}

function FormEditRevTapaMultimedia(modif,revtapacod,revtapamulcod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando datos...</h1>',baseZ: 99999999999});
	var param, url;
	$("#cargando").show();
	param = "revtapacod="+revtapacod;
	if (modif)
		param += "&revtapamulcod="+revtapamulcod;
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_tapa_multimedia_modif.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 250, 
				zIndex: 9999,
				width: 700, 
				position: 'center', 
				modal:true,
				title: "TAPAS", 
				open: function(type, data) {$("#Popup").html(msg); $.unblockUI();}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}

function AltaRevTapaMultimedia(revtapacod)
{
	FormRevTapaMultimedia(0,revtapacod,0);
	return true;
}
function EditarRevTapaMultimedia(revtapamulcod,revtapacod)
{
	FormEditRevTapaMultimedia(1,revtapacod,revtapamulcod);
	return true;
}

function ReordenarRevTapaMultimedia(revtapamulorden)
{
	$("#MsgGuardando").show();
	param  = "revtapamulorden="+revtapamulorden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "rev_tapas_tapa_multimedia_upd.php",
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
	