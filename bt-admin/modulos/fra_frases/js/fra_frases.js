jQuery(document).ready(function(){
	listarFrases();	
});
	
	var timeoutHnd; 
	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarFrases").jqGrid('setGridParam', {url:"fra_frases_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function listarFrases()
	{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarFrases").jqGrid(
	{ 

				url:'fra_frases_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Autor','Frase','Act/Desc','Edit','Del'], 
				colModel:[ {name:'frasecod',index:'frasecod', width:20, align:"center"}, 
						  {name:'fraseautor',index:'fraseautor'}, 	
						  {name:'frasedesclarga',index:'frasedesclarga'},
						  {name:'act',index:'act', width:25, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:25, align:"center", sortable:false},
						  {name:'del',index:'del', width:25, align:"center", sortable:false},
					  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'fraseorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin frases para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
			
			}); 
	
			$(window).bind('resize', function() {
				$("#listarFrases").setGridWidth($("#LstFrases").width());
			}).trigger('resize');
				jQuery("#listarFrases").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
				
			jQuery("#listarFrases").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#listarFrases").jqGrid("getDataIDs");
				   ReordenarFrases(neworder);
			   }}
			 );	
}
function ReordenarFrases(fraseorden)
{
	$("#MsgGuardando").show();
	 
	
	param  = "fraseorden="+fraseorden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "fra_frases_upd.php",
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
	
function FormFrases(modif,frasecod)
{
	var param, url;
	$("#cargando").show();
	param = "";
	
	if (modif)
		param += "&frasecod="+frasecod;
	$.ajax({
	   type: "POST",
	   url: "fra_frases_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				zIndex: 9999999999,
				height: 280, 
				width: 500, 
				position: 'center', 
				modal:false,
				title: "Frases", 
				open: function(type, data) {$("#Popup").html(msg);}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}

function AltaFrase()
{
	FormFrases(0,'');
	return true;
}
	
function EditarFrase(frasecod)
{
	
	FormFrases(1,frasecod);
	return true;
}
	
function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "fra_frases_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
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

function EliminarFrase(frasecod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la Frase?"))
		return false;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Eliminando frase...</h1>',baseZ: 9999999999 })	
	param = "frasecod="+frasecod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(frasecod,tipo)
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando...</h1>',baseZ: 9999999999 })	
	param = "frasecod="+frasecod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

function InsertarFrases()
{
	var param;
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Agregando frase...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
//va al upd con la accion 2
function ModificarFrases(frasecod)
{
	$.blockUI({ message: '<div style="font-size:20px; font-weight:bold"><img src="images/cargando.gif" />Actualizando datos...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=2";
	
	EnviarDatos(param);
	
	return true;
}





