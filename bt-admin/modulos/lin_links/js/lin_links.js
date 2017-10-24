	
	var timeoutHnd; 
function doSearch(ev){ 
	if(timeoutHnd) 
		clearTimeout(timeoutHnd) 
	timeoutHnd = setTimeout(gridReload,500) 
}
function gridReload(){ 
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarLinks").jqGrid('setGridParam', {url:"lin_links_lst_ajax.php?rand="+Math.random(), postData: datos,page:1}).trigger("reloadGrid"); 
} 

function Resetear()
{
//RESETEAR BUSQUEDAS
	//timeoutHnd = setTimeout(gridReload,500) 
}

function ReordenarLink(linkorden)
{	
	$("#MsgGuardando").show();
	 
	param  = "linkorden="+linkorden; 
	param += "&accion=6";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "lin_links_upd.php",
	   data: param,
	   dataType:"json",
	   
	   success: function(msg){ 
			//alert(msg);
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

function listarLinks()
{
	var datos = $("#formbusqueda").serializeObject();
	jQuery("#listarLinks").jqGrid(
	{ 

				url:'lin_links_lst_ajax.php?rand='+Math.random(),
				postData: datos,
				datatype: "json", 
				colNames:['COD','Link','Estado','Editar','Eliminar'], 
				colModel:[ {name:'linkcod',index:'linkcod', width:20, align:"center", hidden:true}, 
						  {name:'linklink',index:'linklink'},
						  {name:'linkestado',index:'linkestado',align:"center", width:20}, 
						  {name:'edit',index:'edit', width:20, align:"center"},
						  {name:'del',index:'del', width:20, align:"center"}	
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				pager: '#pager2', 
				sortname: 'linkorden', 
				viewrecords: true, 
				sortorder: "asc", 
				height:290,
				caption:"",
				emptyrecords: "Sin links para mostrar.",
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
				$("#listarLinks").setGridWidth($("#LstLinks").width());
			}).trigger('resize');
				jQuery("#listarLinks").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false,refresh:false});
			
			jQuery("#listarLinks").jqGrid('sortableRows', 
			 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
			   update : function(e,ui) {
				   var neworder = $("#listarLinks").jqGrid("getDataIDs");
				   ReordenarLink(neworder);
			   }}
			 );	
}

	
	
		
	function EnviarDatos(param)
	{
		$("#MsgGuardando").show();
		$.ajax({
		   type: "POST",
		   url: "lin_links_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){
				if (msg.IsSuccess==true)
					gridReload();
				else
					alert(msg.Msg);	 
				
				$("#MsgGuardando").hide();	
		   }
		 });
	}
	
	function EliminarLinks(linkcod)
	{
		var param;
		if (!confirm("Est\u00e1 seguro que desea eliminar el link?"))
			return false;
		param = "linkcod="+linkcod;
		param += "&accion=3";
		EnviarDatos(param);
	
		return true;
	}

	function ActivarDesactivar(linkcod,tipo)
	{
		var param;
		param = "linkcod="+linkcod;
		param += "&accion="+tipo;
		EnviarDatos(param);
	}
	

	//va al upd con la accion 2
	function Modificarlink()
	{
		var param, url;
		param = $("#formulario").serialize();
		param += "&accion=2";
		EnviarDatosAltaModif(true,param,"Modificando Link...")
		return true;
	}


	function InsertarLink()
	{
		var param, url;
		param = $("#formulario").serialize();
		param += "&accion=1";
		EnviarDatosAltaModif(false,param,"Agregando Link...")
		return true;
	}


	function EnviarDatosAltaModif(modif,param,texto)
	{
		$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> '+texto+'...</h1>',baseZ: 9999999999 })	
		$("#MsgGuardando").show();
		$.ajax({
		   type: "POST",
		   url: "lin_links_upd.php",
		   data: param,
		   dataType:"json",
		   success: function(msg){			
				if (msg.IsSuccess==true)
				{
					alert(msg.Msg);
					if(!modif)
						document.location.href = "lin_links_am.php?linkcod="+msg.linkcod;
					$.unblockUI();
				}else
				{
					alert(msg.Msg);
					$.unblockUI();	
				}
				$("#MsgGuardando").hide();
		   }
		});		
		
		return true;
	}
