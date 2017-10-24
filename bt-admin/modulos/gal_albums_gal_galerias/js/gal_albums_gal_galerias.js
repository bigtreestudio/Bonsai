jQuery(document).ready(function(){
	ListarAlbums();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload1,500) 
}
function gridReload1(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarAlbums").jqGrid('setGridParam', {url:"gal_albums_gal_galerias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


	
function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarAlbumsRelacionados(orden,albumcod)
{
	$("#MsgGuardando").show();
	 
	param  = "orden="+orden; 
	param += "&albumcod="+albumcod;
	param += "&accion=3";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_albums_gal_galerias_upd.php",
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

				url:'gal_albums_gal_galerias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','COD2','Galeria','Eliminar'], 
				colModel:[ {name:'albumcod',index:'albumcod', width:20, align:"center", hidden:true},
						  {name:'galeriacod',index:'galeriacod', width:20, align:"center", hidden:true}, 
						  {name:'galeriatitulo',index:'galeriatitulo',sortable:false}, 
						  {name:'del',index:'del', width:15, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'albumgaleriaorden', 
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
				   albumcod= $("#albumcod").val();
				   ReordenarAlbumsRelacionados(neworder,albumcod);
			   }}
			 );	
}

	
	function BusquedaGaleria(albumcod)
	{
	
		var param = "albumcod="+albumcod;
		$.ajax({
		   type: "POST",
		   url: "gal_albums_gal_galerias_buscar_popup.php",
		   data: param,
		   success: function(msg){
			  //alert(msg);
				$("#ModalGaleria").dialog({
					  height: 510,
					  width: 830,
					  zIndex: 999999999,
					  modal:false,
					  position: 'center',
					  title: "Listado de galerias",
					  open: function(type, data) {$("#ModalGaleria").html(msg);}
	
				});
	
		   }
		 });
	
		return true;
	}


function EliminarAlbumGaleria(albumcod, galeriacod)
{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar la galeria"))
			return false;
		$("#MsgGuardando").show();
		param = "albumcod="+albumcod;
		param += "&galeriacod="+galeriacod;
		param += "&accion=2";
		$.ajax({
		   type: "POST",
		   url: "gal_albums_gal_galerias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){ 
				if (msg.IsSuccess)
				{
					gridReload1();
					alert(msg.Msg);	
					DialogClose()
					
				}else
				{
					alert(msg.Msg);	
				}
				$("#MsgGuardando").hide();
		   }
	});	
}	
	

	function DialogClose()
	{
		 $("#ModalGaleria").dialog("close"); 
	}
