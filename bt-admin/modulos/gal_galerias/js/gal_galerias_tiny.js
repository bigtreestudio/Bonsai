
var timeoutGalHnd; 
function doSearchgaleriaTiny(ev){ 
	if(timeoutGalHnd) 
		clearTimeout(timeoutGalHnd) 
	timeoutGalHnd = setTimeout(gridReloadgaleriatiny,500) 
}
function gridReloadgaleriatiny(){ 
	var datos = $("#formgaleriatiny").serializeObject();
	jQuery("#ListarGaleriasTiny").jqGrid('setGridParam', {url:"not_noticias_galerias_relacionadas_buscar_popup_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 


function ListarGaleriasRelacionadasPopupTiny()
{
	var datos = $("#formgaleriatiny").serializeObject();
	jQuery("#ListarGaleriasTiny").jqGrid(
	{ 

				url:'not_noticias_galerias_relacionadas_buscar_popup_lst_ajax.php?rand='+Math.random(),
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
				pager: '#pagergaleriaTiny', 
				sortname: 'galeriatitulo', 
				viewrecords: true, 
				sortorder: "asc", 
				height:320,
				caption:"",
				emptyrecords: "Sin galerias para mostrar.",
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
				$("#ListarGaleriasTiny").setGridWidth($("#LstGaleriasTiny").width());
			}).trigger('resize');
				jQuery("#ListarGaleriasTiny").jqGrid('navGrid','#pagergaleriaTiny',{edit:false,add:false,del:false,search:false,refresh:false});
			
}

	

function InsertarNoticiaGaleriaRelacionada(titulo,galeriacod)
{
	var html = "<p>$$Tipo='cGalerias' Codigo='"+galeriacod+"'$$</p>";
	var editor = $("#editorid").val();
	tinyMCE.execInstanceCommand(editor,"mceInsertContent",false,html);
	$("#PopupGrafico").dialog("close"); 
	return true;
}
		
