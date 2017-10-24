jQuery(document).ready(function(){
	ListarNoticiasEstado();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarNoticiasEstado").jqGrid('setGridParam', {url:"not_noticias_estados_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#noticiaestadodesc").val("");
	$("#noticiaestadocte").val("");
	$("#noticiaestadomuestracantidad").val("");
	$("#noticiaestadosemuestra").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarNoticiasEstado()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarNoticiasEstado").jqGrid(
	{ 
				url:'not_noticias_estados_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Constante','Muentra Cantidad','Se Muestra','Editar','Eliminar'], 
				colModel:[ {name:'pagestadocod',index:'pagestadocod', width:10, align:"center", hidden:true}, 
						  {name:'pagestadodesc',index:'pagestadodesc'},
						  {name:'pagestadocte',index:'pagestadocte'},
						  {name:'muetracantidad',index:'muetracantidad',width:35}, 
						  {name:'semuestra',index:'semuestra',width:25},
						  {name:'edit',index:'edit',width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'noticiaestadocod', 
				viewrecords: true, 
				sortorder: "asc", 
				height:440,
				caption:"",
				emptyrecords: "Sin estados de las noticias para mostrar.",
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
				$("#ListarNoticiasEstado").setGridWidth($("#LstNoticiasEstado").width());
			}).trigger('resize');
				jQuery("#ListarNoticiasEstado").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}


function FormNoticiasEstado(modif,noticiaestadocod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if(modif)
		param = "noticiaestadocod="+noticiaestadocod;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_estados_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 470, 
				width: 450,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Noticia Estado", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaNoticiasEstado()
{
	FormNoticiasEstado(0,0);
	return true;
}

function EditarNoticiasEstado(noticiaestadocod)
{
	FormNoticiasEstado(1,noticiaestadocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "not_noticias_estados_upd.php",
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

function InsertarNoticiasEstado()
{
	var param;
	param = $("#formnoticiaestado").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}
function ModificarNoticiasEstado()
{
	var param;
	param = $("#formnoticiaestado").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}

function EliminarNoticiasEstado(noticiaestadocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar noticia estado?"))
		return false;
	param = "noticiaestadocod="+noticiaestadocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}