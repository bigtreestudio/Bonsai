jQuery(document).ready(function(){
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoNoticias").jqGrid(
			{ 
				url:'not_noticias_lst_ajax.php?rand='+Math.random(),
				datatype: "json", 
				postData: datos,
				colNames:['Id', 'Titulo','Categoria', 'Fecha','Estado','Acciones'], 
				colModel:[ {name:'noticiacod',index:'noticiacod',classes:"noticiacod_columna",width:20}, 
						  {name:'noticiatitulo',index:'noticiatitulo',classes:"noticiatitulo_columna",width:90}, 
						  {name:'cats_concatenadas',index:'cats_concatenadas',classes:"noticiacategoria_columna",width:30}, 
						  {name:'noticiafecha',index:'noticiafecha', classes:"noticiafecha_columna",width:30, align:"center"}, 
						  {name:'estado',index:'estado', width:30,classes:"noticiaestado_columna",align:"center"}, 
						  {name:'edit',index:'edit', width:60,classes:"noticiaacciones_columna", align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'noticiacod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:240,
				caption:"",
				emptyrecords: "Sin noticias cargadas.",
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},
				loadError : function(xhr,st,err) {
                        alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                }
			}); 
	
			$(window).bind('resize', function() {
				$("#ListadoNoticias").setGridWidth($("#LstNoticias").width());
			}).trigger('resize');

			jQuery("#ListadoNoticias").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
			
			$( "#noticiafecha" ).datepicker( {dateFormat:"dd/mm/yy"});

			
});



var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoNoticias").jqGrid('setGridParam', {url:"not_noticias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
	timeoutHnd = setTimeout(gridReload,500) 
}


var arregloestados = new Array();
function FilterStates(object,estado)
{
	$(".states").removeClass("selected");
	$(object).addClass("selected");
	$("#noticiaestadocod").val(estado);
	gridReload();
}