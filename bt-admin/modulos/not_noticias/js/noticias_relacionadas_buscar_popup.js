
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusquedanoticiarelacionada").serializeObject();
	jQuery("#ListarNoticiasRelacionadas").jqGrid('setGridParam', {url:"not_noticias_relacionadas_buscar_popup_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#galeriatitulo").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


function ListarNoticiasRelacionadasPopup()
{
	var datos = $("#formbusquedanoticiarelacionada").serializeObject();
	jQuery("#ListarNoticiasRelacionadas").jqGrid(
	{ 

				url:'not_noticias_relacionadas_buscar_popup_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Titulo','Categoria','Fecha Alta',''], 
				colModel:[ {name:'noticiacod',index:'noticiacod', width:20, align:"center"}, 
						  {name:'noticiatitulo',index:'noticiatitulo'}, 
						  {name:'catnom',index:'catnom',width:40}, 
						  {name:'noticiafalta',index:'noticiafalta',width:30, align:"center"}, 
						  {name:'ins',index:'ins', width:35, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'n.noticiafalta', 
				viewrecords: true, 
				sortorder: "desc", 
				height:320,
				caption:"",
				emptyrecords: "Sin noticias relacionadas para mostrar.",
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
				$("#ListarNoticiasRelacionadas").setGridWidth($("#LstNoticiasRelacionadas").width());
			}).trigger('resize');
				jQuery("#ListarNoticiasRelacionadas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
}

	
function AbrirPopupNoticiaRelacionas()
{
	
	$.ajax({
	   type: "POST",
	   url: "not_noticias_relacionadas_buscar_popup.php",
	   success: function(msg){
		  //alert(msg);
			$("#PopupNoticiasRelacionadas").dialog({
				  height: 550,
				  width: 830,
				  zIndex: 9999999999999,
				  modal:false,
				  position: 'center',
				  title: "Listado de Noticias",
				  open: function(type, data) {$("#PopupNoticiasRelacionadas").html(msg);}

			});

	   }
	 });

	return true;
}

function InsertarNoticiaRelacionada(noticiatitulo,cod)
{
	
	$("#noticiafinder").val(noticiatitulo); 
	$("#noticiacodrel").val(cod); 
	DialogClose();	

}

function DialogClose()
{
	 $("#PopupNoticiasRelacionadas").dialog("close"); 
}

		
