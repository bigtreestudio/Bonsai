jQuery(document).ready(function(){
	ListarAreas();	
});


function gridReloadAreas(){ 
	var datos = $("#formplantillasareas").serializeObject();
	jQuery("#ListarAreas").jqGrid('setGridParam', {url:"tap_plantillas_areas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function ListarAreas()
{
	var datos = $("#formplantillasareas").serializeObject();
	jQuery("#ListarAreas").jqGrid(
	{ 

				url:'tap_plantillas_areas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Area','Eliminar'], 
				colModel:[ {name:'areacod',index:'areacod', width:20, align:"center", hidden:true}, 
						  {name:'descripcion',index:'descripcion', sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false},
					  ], 
				ajaxGridOptions: {cache: false},
				mtype: "POST",
				sortname: 'areacod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:200,
				width:80,
				caption:"",
				emptyrecords: "Sin Areas cargadas.",
				loadError : function(xhr,st,err) {
                        alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						alert("Error al procesar los datos");
                },
			
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarAreas").setGridWidth($("#LstAreas").width());
			}).trigger('resize');
			
			jQuery("#ListarAreas").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarAreas").jqGrid("getDataIDs");
				   plantcod= $("#plantcod").val();
				   ReordenarAreas(neworder,plantcod);
			   }}
			 );	
			
			
}
	

function ReordenarAreas(orden,plantcod)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&plantcod="+plantcod;
	param += "&accion=2";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_areas_upd.php",
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

	
function EnviarDatosArea(param)
{
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_areas_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReloadAreas();
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



function EliminarArea(plantcod,areacod)
{
	var param;
	if(!confirm("Esta seguro que desea eliminar el area?"))
		return false;
	
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Eliminando area...</h1>',baseZ: 9999999999 })	
	param = "areacod="+areacod+"&plantcod="+plantcod+"&accion=3";
	EnviarDatosArea(param);
	return true;

}



function AgregarArea()
{
	var param;
	if($("#areahtmlcod").val()==""){
		alert("Debe ingresar un area");
		return false;
	}
	
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Agregando area...</h1>',baseZ: 9999999999 })	
	param = $("#formplantillasareas").serialize();
	param += "&accion=1";
	EnviarDatosArea(param);
	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

