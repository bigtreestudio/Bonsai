jQuery(document).ready(function(){
	ListarTemas();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTemas").jqGrid('setGridParam', {url:"tem_temas_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#ventacod").val("");
	$("#tipoventacod").val("");
	$("#metodopagocod").val("");
	$("#tipofacturacod").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarTemas()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarTemas").jqGrid(
	{ 

				url:'tem_temas_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','T\u00edtulo','Color','Estado','Editar','Eliminar'], 
				colModel:[ {name:'temacod',index:'temacod', width:20, align:"center", hidden:true}, 
						  {name:'tematitulo',index:'tematitulo'},
						  {name:'color',index:'color', width:15, align:"center", sortable:false},
						  {name:'estado',index:'estado',align:"center", width:15, sortable:false}, 
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'temacod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin temas para mostrar.",
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
				$("#ListarTemas").setGridWidth($("#LstTemas").width());
			}).trigger('resize');
				jQuery("#ListarTemas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}

	
function FormTemas(modif,temacodsuperior,temacod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando formulario...</h1>',baseZ: 99999999999});
	var param, url;
	$("#cargando").show();
	param = "temacodsuperior="+temacodsuperior;
	if (modif)
		param += "&temacod="+temacod;
	$.ajax({
	   type: "POST",
	   url: "tem_temas_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 400, 
				zIndex: 9999,
				width: 620, 
				position: 'center', 
				modal:true,
				title: "Tema", 
				open: function(type, data) {$("#Popup").html(msg); initTextEditors(); $.unblockUI();}
			});
			$("#cargando").hide();
	   }
	 });

	return true;
}


function AltaTemas(temacodsuperior)
{
	FormTemas(0,temacodsuperior,0);
	return true;
}
function EditarTemas(temacod,temacodsuperior)
{
	FormTemas(1,temacodsuperior,temacod);
	return true;
}


function ValidarJs()
{
	if ($("#tematitulo").val()=="")
	{
		alert("Debe ingresar un t\u00edtulo");
		$("#tematitulo").focus();
		return false;
	}

	return true;
}


function EnviarDatos(param)
{
	$.ajax({
	   type: "POST",
	   url: "tem_temas_upd.php",
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

function InsertarTemas()
{
	var param;
	if (!ValidarJs())
		return false;

	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Agregando tema...</h1>',baseZ: 99999999999});
	var temadesc = tinyMCE.get('temadesc');
	$("#temadesc").val(temadesc.getContent());
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}


function ModificarTemas()
{
	var param;
	if (!ValidarJs())
		return false;

	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Modificando tema...</h1>',baseZ: 99999999999});
	var temadesc = tinyMCE.get('temadesc');
	$("#temadesc").val(temadesc.getContent());
	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}


function EliminarTemas(temacod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar la descripci\u00f3n?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando tema...</h1>',baseZ: 99999999999});
	param = "temacod="+temacod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(temacod,tipo)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando...</h1>',baseZ: 99999999999});
	var param;
	param = "temacod="+temacod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}

