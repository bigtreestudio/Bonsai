//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	ListarNoticiasRelacionadas($("#noticiacod").val());
	AutocompleteNoticiasRelacionadas();
});


	function BusquedaNoticia(noticiacod)
	{
	
		var param = "noticiacod="+noticiacod;
		$.ajax({
		   type: "POST",
		   url: "not_noticias_rel_buscar_popup.php",
		   data: param,
		   success: function(msg){
			  //alert(msg);
				$("#ModalNoticia").dialog({
					  height: 510,
					  width: 830,
					  zIndex: 999999999,
					  modal:false,
					  position: 'center',
					  title: "Listado de noticias",
					  open: function(type, data) {$("#ModalNoticia").html(msg);}
	
				});
	
		   }
		 });
	
		return true;
	}

function AutocompleteNoticiasRelacionadas()
{
	jQuery("#noticiafinder").autocomplete({
		minLength: 3,
		delay : 400,
		source: function(request, response) {
			jQuery.ajax({
			   url:      "not_noticias_busqueda_autocomplete.php",
			   data:  {
						mode : "ajax",
						component : "1",
						searcharg : "titulo",
						task : "titulo",
						limit : 15,
						term : request.term
				},
			   dataType: "json",
			   success: function(data)  {
				 response(data);
			  }
 
			})
	   },
 
	   select:  function(e, ui) {
			var noticiacod= ui.item.noticiacod;
			$("#noticiacodrel").val(noticiacod);
		}
	});
}



function AgregarNoticiaRelacionadaAutocomplete()
{
	if ($("#noticiacod").val()=="")
	{
		alert("Debe guardar la noticia antes de relacionar una galeria");
		$("#noticiafinder").focus();
		return false;			
	}
	if ($("#noticiacodrel").val()=="")
	{
		alert("Debe seleccionar una noticia a relacionar");
		$("#noticiafinder").focus();
		return false;			
	}
	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&noticiacodrel="+$("#noticiacodrel").val(); 
	param += "&accion=1";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				NoticiasRelacionadasReload();
				$("#noticiacodrel").val("");
				$("#noticiafinder").val("");
				$("#msgnoticiarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}


function EliminarNoticiaRelacionada(noticiacodrel)
{
	if (!confirm("Est\u00e1 seguro que desea eliminar la noticia relacionada?"))
		return false;
	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&noticiacodrel="+noticiacodrel; 
	param += "&accion=2";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				NoticiasRelacionadasReload();
				$("#msgnoticiarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}


function DestacarNoticiaRelacionada(tipo,noticiacodrel)
{
	var accion = 4;
	if (tipo==0)
		accion = 5;

	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&noticiacodrel="+noticiacodrel; 
	param += "&accion="+accion;
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				NoticiasRelacionadasReload();
				$("#msgnoticiarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



function ReordenarNoticiasRelacionadas(orden)
{
	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&noticia="+orden; 
	param += "&accion=3";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_noticias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$("#msgnoticiarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



function ListarNoticiasRelacionadas(noticiacod)
{
	jQuery("#ListadoNoticiasRelacionadas").jqGrid(
			{ 
				url:'not_noticias_rel_lst_ajax.php?noticiacod='+noticiacod+'&rand='+Math.random(),
				datatype: "json", 
				colNames:['Id', 'Titulo','Categoria','Fecha','',''], 
				colModel:[ {name:'noticiacod',index:'noticiacod', width:30, sortable:false}, 
						  {name:'noticiatitulo',index:'noticiatitulo', sortable:false}, 
						  {name:'catnom',index:'catnom',width:40,sortable:false}, 
						  {name:'noticiafecha',index:'noticiafecha', width:30, align:"center", sortable:false}, 
						  {name:'del',index:'del', width:33, align:"center", sortable:false},
						  {name:'des',index:'des', width:30, align:"center", sortable:false}
						  ], 
				rowNum:20, 
				ajaxGridOptions: {cache: false},
				rowList:[20,40,60],
				mtype: "POST",
				viewrecords: true, 
				height:240,
				width: 650,
				caption:"",
				emptyrecords: "Sin noticias relacionadas cargadas.",
				loadError : function(xhr,st,err) {
                        alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
				gridComplete: function() { $("#ListadoNoticiasRelacionadas").addClass("drop"); }
			}); 
			if ($("#puedemodificar").val()==1)
			{
				jQuery("#ListadoNoticiasRelacionadas").jqGrid('sortableRows', 
				 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
				   update : function(e,ui) {
					   var neworder = $("#ListadoNoticiasRelacionadas").jqGrid("getDataIDs");
					   ReordenarNoticiasRelacionadas(neworder);
				   }}
				 );
			}
}

function NoticiasRelacionadasReload(){ 
	var datos = $("#noticiacod").val();
	jQuery("#ListadoNoticiasRelacionadas").jqGrid('setGridParam', {url:"not_noticias_rel_lst_ajax.php?noticiacod="+datos+"rand="+Math.random(),page:1}).trigger("reloadGrid"); 
} 
