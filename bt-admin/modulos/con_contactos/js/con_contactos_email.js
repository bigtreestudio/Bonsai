

function gridReload(){ 
	var datos = $("#formbusquedaemail").serializeObject();
	jQuery("#ListarEmails").jqGrid('setGridParam', {url:"con_contactos_email_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

	function ListarEmails()
	{
	var datos = $("#formbusquedaemail").serializeObject();
	jQuery("#ListarEmails").jqGrid(
	{ 

				url:'con_contactos_email_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Email','Tipo','Del'], 
				colModel:[ {name:'enviocod',index:'enviocod', width:20, align:"center", hidden:true}, 
						  {name:'envioemail',index:'envioemail'},
						  {name:'enviotipo',index:'enviotipo'},
						  {name:'del',index:'del', width:20, align:"center", sortable:false},
					  ], 
				ajaxGridOptions: {cache: false},
				mtype: "POST",
				pager: '#pager3', 
				sortname: 'enviocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:120,
				width:80,
				caption:"",
				emptyrecords: "Sin Emails para mostrar.",
				loadError : function(xhr,st,err) {
                       alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						alert("Error al procesar los datos");
                },
			
			}); 
	
			$(window).bind('resize', function() {
				$("#ListarEmails").setGridWidth($("#LstEmails").width());
			}).trigger('resize');
				jQuery("#ListarEmails").jqGrid('navGrid','#pager3',{edit:false,add:false,del:false,search:false,refresh:false});
			
}
	
	
	
function EnviarDatosEmail(param)
{
	$.ajax({
	   type: "POST",
	   url: "con_contactos_email_upd.php",
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



function EliminarEmail(enviocod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el Email?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Eliminando opci\u00f3n...</h1>',baseZ: 9999999999 })	
	param = "enviocod="+enviocod;
	param += "&accion=2";
	EnviarDatosEmail(param);

	return true;
}



function InsertarEmail()
{
	var param;
	if($("#enviomail").val()==""){
		alert("Debe ingresar un valor Email");
		return false;
		}
	if($("#enviotipo").val()==""){
		alert("Debe ingresar un tipo de envio");
		return false;
		}		
	
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Agregando opci\u00f3n...</h1>',baseZ: 9999999999 })	
	param = $("#formbusquedaemail").serialize();
	param += "&accion=1";
	EnviarDatosEmail(param);

	
	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}
