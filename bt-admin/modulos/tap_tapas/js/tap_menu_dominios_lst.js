var idListado;
var idPopupDetalle;

jQuery(document).ready(function(){
   if($('#menucod').val() === undefined){
		idListado="#ListarDominios_"+Math.random();
		idPopupDetalle="#PopupDetalleDominioMenu_"+Math.random();
	}else {
		idListado="#ListarDominios_"+$('#menucod').val();
		idPopupDetalle="#PopupDetalleDominioMenu_"+$('#menucod').val();
    }	

	ListarDominios(idListado);			
});

var timeoutHnd; 	
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload(idListado),500) 
}
function gridReload(idListado){ 
  
	var datos = $("#formbusqueda").serializeObject();
	jQuery(idListado).jqGrid('setGridParam', {url:"dominios_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 




function ListarDominios(idListado)
{

	var datos = $("#formbusqueda").serializeObject();
	jQuery(idListado).jqGrid(
	{ 
				url:'dominios_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Dominio','Tipo','Agregar'], 
				colModel:[ {name:'codigo',index:'codigo', width:20, align:"center", hidden:true}, 
						  {name:'dominio',width:400,index:'dominio'},
						  {name:'tipo',index:'tipo', width:200, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:105, align:"center", sortable:false},
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				zindex:999999999999,
				sortname: 'tipo', 
				viewrecords: true, 
				sortorder: "asc", 
				height:220,
				caption:"",
				emptyrecords: "Sin dominios para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                       //alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
}

function DialogCloseDominio()
{
	 $(idPopupDetalle).dialog("close"); 
}

function AgregarDominio(codigo)
{
	 $("#menulink").val('/'+$("#dominiosite_"+codigo).val());
	 $(idPopupDetalle).dialog("close"); 
}


