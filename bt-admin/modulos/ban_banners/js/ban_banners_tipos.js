jQuery(document).ready(function(){
	ListarBannersTipos();			
});

	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarBannersTipos").jqGrid('setGridParam', {url:"ban_banners_tipos_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	$("#bannertipodesc").val("");
	$("#bannerancho").val("");
	$("#banneralto").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarBannersTipos()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarBannersTipos").jqGrid(
	{ 
				url:'ban_banners_tipos_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci\u00f3n','Ancho','Alto','Editar','Eliminar'], 
				colModel:[ {name:'bannertipocod',index:'bannertipocod', width:20, align:"center", hidden:true}, 
						  {name:'bannertipodesc',index:'bannertipodesc'}, 
  						  {name:'bannerancho',index:'bannerancho'}, 
						  {name:'banneralto',index:'banneralto'},
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false},
						  {name:'del',index:'del', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'bannertipocod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:440,
				caption:"",
				emptyrecords: "Sin tipos de banners para mostrar.",
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
				$("#ListarBannersTipos").setGridWidth($("#LstBannersTipos").width());
			}).trigger('resize');
				jQuery("#ListarBannersTipos").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}


function FormBannersTipos(modif,bannertipocod)
{
	
	var param, url;
	$("#cargando").show();
	param = "";
	if (modif)
		param = "bannertipocod="+bannertipocod;
	
	$.ajax({
	   type: "POST",
	   url: "ban_banners_tipos_am.php",
	   data: param,
	   success: function(msg){
			$("#Popup").dialog({	
				height: 350, 
				width: 300,
				zIndex: 999999999, 
				position: 'center', 
				modal:false,
				title: "Tipo Banner", 
				open: function(type, data) {$("#Popup").html(msg); }
			});
			$("#cargando").hide();
	   
	   }
	 
	 }
	 
	 );
	
	return true;
}


function AltaBannersTipos()
{
	FormBannersTipos(0,0);
	return true;
}

function EditarBannersTipos(bannertipocod)
{
	FormBannersTipos(1,bannertipocod);
	return true;
}

function EnviarDatos(param)
{
	 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "ban_banners_tipos_upd.php",
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

function InsertarBannersTipos()
{
	var param;
	param = $("#formmultformato").serialize();
	param += "&accion=1";
	EnviarDatos(param);
	
	return true;
}

function ModificarBannersTipos()
{
	var param;
	param = $("#formmultformato").serialize();
	param += "&accion=2";
	EnviarDatos(param);
	
	return true;
}



function EliminarBannersTipos(bannertipocod)
{
	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el tipo de banners?"))
		return false;
	param = "bannertipocod="+bannertipocod;
	param += "&accion=3";
	EnviarDatos(param);

	return true;
}

function DialogClose()
{
	 $("#Popup").dialog("close"); 
}