
	var timeoutHnd; 
function doSearchgaleria(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReloadgaleria,500) 
}
function gridReloadgaleria(){ 
	var datos = $("#formbusquedanoticiagaleriarelacionada").serializeObject();
	jQuery("#ListarNoticiasGaleriasRelacionadas").jqGrid('setGridParam', {url:"not_noticias_galerias_relacionadas_buscar_popup_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

	
function Resetear()
{
	$("#galeriatitulo").val("");
	timeoutHnd = setTimeout(gridReloadgaleria,500) 
}


function ListarGaleriasRelacionadasPopup()
{
	var datos = $("#formbusquedanoticiagaleriarelacionada").serializeObject();
	jQuery("#ListarNoticiasGaleriasRelacionadas").jqGrid(
	{ 

				url:'not_noticias_galerias_relacionadas_buscar_popup_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Titulo',''], 
				colModel:[ {name:'noticiacod',index:'noticiacod', width:20, align:"center", hidden:true}, 
						  {name:'noticiatitulo',index:'noticiatitulo',sortable:false}, 
						  {name:'ins',index:'ins', width:30, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'galeriatitulo', 
				viewrecords: true, 
				sortorder: "asc", 
				height:320,
				caption:"",
				emptyrecords: "Sin galerias relacionadas para mostrar.",
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
				$("#ListarNoticiasGaleriasRelacionadas").setGridWidth($("#LstNoticiasGaleriasRelacionadas").width());
			}).trigger('resize');
				jQuery("#ListarNoticiasGaleriasRelacionadas").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
}

	
function AbrirPopupGaleriaRelacionada()
{
	$.ajax({
	   type: "POST",
	   url: "not_noticias_galerias_relacionadas_buscar_popup.php",
	   success: function(msg){
		  //alert(msg);
			$("#PopupGaleriasRelacionadas").dialog({
				  height: 510,
				  width: 830,
				  zIndex: 9999999999999,
				  modal:false,
				  position: 'center',
				  title: "Listado de galerias",
				  open: function(type, data) {$("#PopupGaleriasRelacionadas").html(msg);}

			});

	   }
	 });

	return true;
}

function InsertarNoticiaGaleriaRelacionada(galeriatitulo,cod)
{
	
	$("#galeriafinder").val(galeriatitulo); 
	$("#galeriacodrel").val(cod); 
	DialogGaleriaClose();	

}

function DialogGaleriaClose()
{
	 $("#PopupGaleriasRelacionadas").dialog("close"); 
}

		
