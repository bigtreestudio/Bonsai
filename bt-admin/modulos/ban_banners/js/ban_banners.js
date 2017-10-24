$(document).ajaxStart($.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando banners...</h1>',baseZ: 9999999999  })).ajaxStop($.unblockUI);


var timeoutHnd; 
//FUNCION QUE REALIZA LA BUSQUEDA DE LOS CAMPOS INGRESADOS EN EL KEYPRESS

function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}

//FUNCION QUE RECARGA LA GRILLA

function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoBanners").jqGrid('setGridParam', {url:"ban_banners_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 
    

//FUNCION QUE LIMPIA LOS CAMPOS DE BUSQUEDA

function Resetear()
{
	$("#bannerdesc").val("");
	$("#bannertipocod").val("");
	timeoutHnd = setTimeout(gridReload,500) 
}


//FUNCION QUE ARGMA LA GRILLA DE LOS DATOS DE LOS BANNERS
//DATOS DE ENTRADA:
//      FILTROS DE BUSQUEDA: 
//				BANNERDESC = DESCRIPCION DEL BANNER
//				BANNERTIPOCOD = TIPO DE BANNER
//				ESTADO = ESTADO DEL BANNER

function listarbanners()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#ListadoBanners").jqGrid(
	{ 

				url:'ban_banners_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Descripci&oacute;n','Tipo de Banner','Url','Clicks','Editar'], 
				colModel:[ {name:'bannercod',index:'bannercod', width:20, align:"center"}, 
						  {name:'bannerdesc',index:'bannerdesc'},
						  {name:'bannertipocod',index:'bannertipocod', width:90},
						  {name:'bannerurl',index:'bannerurl', width:70}, 
						  {name:'bannercontador',index:'bannercontador',  align:"center",width:50}, 
						  {name:'edit',index:'edit', width:30, align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'bannercod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:280,
				caption:"",
				emptyrecords: "Sin banners cargados.",
				loadError : function(xhr,st,err) {
                        alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                }
			}); 
	
			$(window).bind('resize', function() {
				$("#ListadoBanners").setGridWidth($("#LstBanners").width());
			}).trigger('resize');
				jQuery("#ListadoBanners").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
}
