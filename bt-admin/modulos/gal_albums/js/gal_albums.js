jQuery(document).ready(function(){
	ListarAlbums();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarAlbums").jqGrid('setGridParam', {url:"gal_albums_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarAlbumsRelacionados(orden)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&accion=4";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_albums_upd.php",
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

function ListarAlbums()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarAlbums").jqGrid(
	{ 

				url:'gal_albums_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Titulo','Estado','Armar Galeria','Previsualizar','Editar','Eliminar'], 
				colModel:[ {name:'catcod',index:'catcod', width:20, align:"center", hidden:true}, 
						  {name:'albumtitulo',index:'albumtitulo', sortable:false},
						  {name:'albumestadocod',index:'albumestadocod',align:"center", width:15, sortable:false}, 
						  {name:'armar',index:'armar', width:20, align:"center", sortable:false},
						  {name:'linkprev',index:'linkprev', width:20, align:"center", sortable:false},
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
				height:440,
				caption:"",
				emptyrecords: "Sin albums para mostrar.",
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
				$("#ListarAlbums").setGridWidth($("#LstAlbums").width());
			}).trigger('resize');
				jQuery("#ListarAlbums").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			jQuery("#ListarAlbums").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#ListarAlbums").jqGrid("getDataIDs");
				   ReordenarAlbumsRelacionados(neworder);
			   }}
			 );	
}

	
	function FormAlbums(modif,albumsuperior,albumcod)
	{
		var param, url;
		$("#cargando").show();
		param = "albumsuperior="+albumsuperior;
		if (modif)
			param += "&albumcod="+albumcod;
		$.ajax({
		   type: "POST",
		   url: "gal_albums_am.php",
		   data: param,
		   success: function(msg){
				$("#Popup").dialog({	
					height: 480, 
					width: 550, 
					position: 'center', 
					modal:true,
					title: "Albums", 
					open: function(type, data) {$("#Popup").html(msg);$(".chzn-select").chosen();}
				});
				$("#cargando").hide();
		   }
		 });
	
		return true;
	}


	function AltaAlbums(albumsuperior)
	{
		FormAlbums(0,albumsuperior,0);
		return true;
	}
	function EditarAlbums(albumcod,albumsuperior)
	{
		FormAlbums(1,albumsuperior,albumcod);
		return true;
	}
	
	
	function ValidarJs()
	{
		if ($("#albumtitulo").val()=="")
		{
			alert("Debe ingresar un titulo");
			$("#albumtitulo").focus();
			return false;
		}
	
		return true;
	}
	
	
	function EnviarDatos(param)
	{
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 2000 })	
		$.ajax({
		   type: "POST",
		   url: "gal_albums_upd.php",
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
	
	function InsertarAlbums()
	{
		var param;
		if (!ValidarJs())
			return false;

		param = $("#formulario").serialize();
		param += "&accion=1";
		EnviarDatos(param);
		
		return true;
	}


	function ModificarAlbum()
	{
		var param;
		if (!ValidarJs())
			return false;

		param = $("#formulario").serialize();
		param += "&accion=2";
		EnviarDatos(param);
		
		return true;
	}


	function EliminarAlbum(albumcod)
	{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar el album"))
			return false;
		param = "albumcod="+albumcod;
		param += "&accion=3";
		EnviarDatos(param);
	
		return true;
	}

	function DialogClose()
	{
		 $("#Popup").dialog("close"); 
	}
	
	function CargarMenu()
	{
		var param="tipo=4&menutipocod="+$("#menutipocod").val();
		$("#Menus").html("Cargando menu...");	
		$.ajax({
		   type: "POST",
		   url: "combo_ajax.php",
		   data: param,
		   dataType:"html",
		   success: function(msg){
				$("#Menus").html(msg);	 
				$(".chzn-select").chosen();
		   }
		   
		 });
	}