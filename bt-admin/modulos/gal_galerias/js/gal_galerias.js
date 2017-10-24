jQuery(document).ready(function(){
	listarGalerias();	
});
	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarGalerias").jqGrid('setGridParam', {url:"gal_galerias_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarGaleriasRelacionadas(galeriaorden)
{
	$("#MsgGuardando").show();
	 
	param  = "galeriaorden="+galeriaorden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "gal_galerias_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function listarGalerias()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarGalerias").jqGrid(
	{ 

				url:'gal_galerias_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Titulo','Estado','Previsualizar','Editar','Galeria','Eliminar'], 
				colModel:[ {name:'galeriacod',index:'galeriacod', width:20, align:"center", hidden:true}, 
						  {name:'galeriatitulo',index:'galeriatitulo', sortable:false},
						  {name:'galeriaestadocod',index:'galeriaestadocod',align:"center", width:20, sortable:false}, 
						  {name:'prev',index:'prev', width:20, align:"center", sortable:false},
						  {name:'edit',index:'edit', width:20, align:"center", sortable:false},
						  {name:'multimediaconjuntocod',index:'multimediaconjuntocod', width:20, align:"center", sortable:false},
						  {name:'del',index:'del', width:20, align:"center", sortable:false}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'galeriacod', 
				viewrecords: true, 
				sortorder: "desc", 
				height:440,
				caption:"",
				emptyrecords: "Sin galerias para mostrar.",
				/*
				ondblClickRow: function(rowid) {
					document.location.href=$("#editar_"+rowid).attr('href');
				},*/
				loadError : function(xhr,st,err) {
                      // alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
					
				
			}); 
	
			$(window).bind('resize', function() {
				$("#listarGalerias").setGridWidth($("#LstGalerias").width());
			}).trigger('resize');
				jQuery("#listarGalerias").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
			/*jQuery("#listarGalerias").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#listarGalerias").jqGrid("getDataIDs");
				   ReordenarGaleriasRelacionadas(neworder);
			   }}
			 );	*/
}

	
	
	function EnviarDatos(param)
	{
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Procesando...</h1>',baseZ: 9999999999 })	
		$.ajax({
		   type: "POST",
		   url: "gal_galerias_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
			if (msg.IsSucceed==true)
			{
				gridReload();
				alert(msg.Msg);
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
	
	function EliminarGalerias(galeriacod)
	{
		var param;
		if (!confirm("Esta seguro que desa eliminar esta galeria?"))
			return false;
		param = "galeriacod="+galeriacod;
		param += "&accion=3";
		EnviarDatos(param);
	
		return true;
	}

	function ActivarDesactivar(galeriacod,tipo)
	{
		var param;
		param = "galeriacod="+galeriacod;
		param += "&accion="+tipo;
		EnviarDatos(param);
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

