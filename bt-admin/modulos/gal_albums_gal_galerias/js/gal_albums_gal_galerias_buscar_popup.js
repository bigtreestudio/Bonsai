jQuery(document).ready(function(){
	ListarGalerias();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusquedagaleria").serializeObject();
	jQuery("#ListarGalerias").jqGrid('setGridParam', {url:"gal_albums_gal_galerias_buscar_popup_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function gridReload2(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListarAlbums").jqGrid('setGridParam', {url:"gal_albums_gal_galerias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#galeriatitulo").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarGalerias()
{
	var datos = $("#formbusquedagaleria").serializeObject();
	jQuery("#ListarGalerias").jqGrid(
	{ 

				url:'gal_albums_gal_galerias_buscar_popup_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Galeria',''], 
				colModel:[ {name:'galeriacod',index:'galeriacod', width:20, align:"center", hidden:true}, 
						  {name:'galeriatitulo',index:'galeriatitulo',sortable:false}, 
						  {name:'ins',index:'ins', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'galeriaorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:320,
				caption:"",
				emptyrecords: "Sin galeria para mostrar.",
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
				$("#ListarGalerias").setGridWidth($("#LstGalerias").width());
			}).trigger('resize');
				jQuery("#ListarGalerias").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
}

	
		
function InsertarGaleria(albumcod,galeriacod)
{
		$("#MsgGuardando").show();
		var param, url;
		param = "albumcod="+albumcod;
		param += "&galeriacod="+galeriacod;
		param += "&accion=1";
		$.ajax({
		   type: "POST",
		   url: "gal_albums_gal_galerias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){ 
				if (msg.IsSuccess)
				{
					gridReload2();
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