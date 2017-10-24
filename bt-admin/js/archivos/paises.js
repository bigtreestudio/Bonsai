jQuery(document).ready(function(){
	ListarPaises();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPaises").jqGrid('setGridParam', {url:"paises_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#feriadosmes").val("");
	$("#feriadosano").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarPaises()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarPaises").jqGrid(
	{ 
				url:'paises_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Nombre','Act/Desc','Editar','Eliminar'], 
				colModel:[ {name:'paiscod',index:'paiscod', width:20, align:"center", hidden:true}, 
						  {name:'paisdesc',index:'paisdesc'},
						  {name:'act/desc',index:'act/desc', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:15, align:"center", sortable:false},
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'paisdesc', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin paises para mostrar.",
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
				$("#ListarPaises").setGridWidth($("#LstPaises").width());
			}).trigger('resize');
				jQuery("#ListarPaises").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}


function FormPais(modif,paiscod)
{
	var param, url;
	$("#cargando").show();
	if (modif)
		param = "paiscod="+paiscod;
	
	$.ajax({
   type: "POST",
   url: "paises_am.php",
   data: param,
   success: function(msg){
		$("#Popup").dialog({	
			height: 200, 
			width: 550,
			zIndex: 999999999, 
			position: 'center', 
			modal:false,
			title: "Paises", 
			open: function(type, data) {$("#Popup").html(msg); }
		});
		$("#cargando").hide();
   
	   }
	 
	 }
	 
	 );

	return true;
}

function AltaPais()
{
	FormPais(0,0);
	return true;
}
function EditarPais(paiscod)
{
	FormPais(1,paiscod);
	return true;
}
	
	
function EnviarDatos(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "paises_upd.php",
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
	
function InsertarPais()
{
	var param;
	param = "paisdesc="+$("#paisdesc").val();
	param += "&idiomacod="+$("#idiomacod").val();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}


function ModificarPais()
{
	var param;
	param = "paiscod="+$("#paiscod").val();
	param += "&paisdesc="+$("#paisdesc").val();
	param += "&idiomacod="+$("#idiomacod").val();
	param += "&accion=2";
	
	EnviarDatos(param);
	
	return true;
}


function EliminarPais(paiscod)
{
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el pais?"))
		return false;
	param = "paiscod="+paiscod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function ActivarDesactivar(paiscod,tipo)
{
	var param;
	param = "paiscod="+paiscod;
	param += "&accion="+tipo;
	EnviarDatos(param);
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}
