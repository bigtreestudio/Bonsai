

function gridReload(){ 
	var datos = $("#formbusquedaopcion").serializeObject();
	jQuery("#listarEncuestasOpciones").jqGrid('setGridParam', {url:"enc_encuestas_opciones_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarEncuestasOpciones()
	{
	var datos = $("#formbusquedaopcion").serializeObject();
	jQuery("#listarEncuestasOpciones").jqGrid(
	{ 

				url:'enc_encuestas_opciones_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Edit','Del'], 
				colModel:[ {name:'opcioncod',index:'opcioncod', width:20, align:"center", hidden:true}, 
						  {name:'opcionnombre',index:'opcionnombre', width:60,height:40, sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
					  ], 
				ajaxGridOptions: {cache: false},
				mtype: "POST",
				pager: '#pager3', 
				sortname: 'opcioncod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:140,
				width:80,
				caption:"",
				emptyrecords: "Sin opciones para mostrar.",
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
			
			}); 
	
			$(window).bind('resize', function() {
				$("#listarEncuestasOpciones").setGridWidth($("#LstEncuestasOpciones").width());
			}).trigger('resize');
				jQuery("#listarEncuestasOpciones").jqGrid('navGrid','#pager3',{edit:false,add:false,del:false,search:false,refresh:false});
			
			jQuery("#listarEncuestasOpciones").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#listarEncuestasOpciones").jqGrid("getDataIDs");
				   ReordenarEncuestasOpciones(neworder);
			   }}
			 );	
}
	
	
	
	
function EditarEncuestasOpciones(opcioncod)
{
	$("#opcionnombre").val($("#titulo_"+opcioncod).html())
	$("#opciocodmodif").val(opcioncod);
	$("#Guardar").hide();
	$("#Modificar").show();
	$("#Limpiar").show();

	return true;
}

function Limpiar()
{
	
	$("#opcionnombre").val("");
	$("#opciocodmodif").val("");
	$("#Limpiar").hide();
	$("#Modificar").hide();
	$("#Guardar").show();

	return true;
}
	
function ReordenarEncuestasOpciones(opcionorden)
{
	$("#MsgGuardando").show();
	 
	param  = "opcionorden="+opcionorden; 
	param += "&accion=4";

	var param, url;
	$.ajax({
	   type: "POST",
	   url: "enc_encuestas_opciones_upd.php",
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

	
function EnviarDatosOpcional(param)
{
	$.ajax({
	   type: "POST",
	   url: "enc_encuestas_opciones_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			gridReload();
			$("#opcionnombre").val("");
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



function EliminarEncuestaOpcion(opcioncod,encuestacod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la Opcion?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Eliminando opci\u00f3n...</h1>',baseZ: 9999999999 })	
	param = "opcioncod="+opcioncod;
	param += "&encuestacod="+encuestacod;
	param += "&accion=3";
	EnviarDatosOpcional(param);

	return true;
}



function InsertarEncuestasOpciones()
{
	var param;
	if($("#opcionnombre").val()==""){
		alert("Debe ingresar un valor a la opci\u00f3n");
		return false;
		}	
	
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Agregando opci\u00f3n...</h1>',baseZ: 9999999999 })	
	param = $("#formbusquedaopcion").serialize();
	param += "&accion=1";
	EnviarDatosOpcional(param);
	
	return true;
}



function ModificarEncuestasOpciones(opcioncod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Modificando opci\u00f3n...</h1>',baseZ: 9999999999 })	
	param = $("#formbusquedaopcion").serialize();
	param += "&accion=2";
	EnviarDatosOpcional(param);
	return true;
}



function DialogClose()
{
	 $("#Popup").dialog("close"); 
}
