//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	ListarGaleriasRelacionadas($("#noticiacod").val());
	AutocompleteGaleriasRelacionadas();
});


function AutocompleteGaleriasRelacionadas()
{
	jQuery("#galeriafinder").autocomplete({
		minLength: 2,
		delay : 400,
		source: function(request, response) {
			jQuery.ajax({
			   url:      "gal_galerias_busqueda_autocomplete.php",
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
			var galeriacod= ui.item.galeriacod;
			$("#galeriacodrel").val(galeriacod);
		}
	});
}



function AgregarGaleriaRelacionadaAutocomplete()
{
	if ($("#noticiacod").val()=="")
	{
		alert("Debe guardar la noticia antes de relacionar una galeria");
		$("#galeriafinder").focus();
		return false;			
	}
	
	if ($("#galeriacodrel").val()=="")
	{
		alert("Debe seleccionar una galeria a relacionar");
		$("#galeriafinder").focus();
		return false;			
	}

	$("#MsgGuardando").show();
	
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&galeriacod="+$("#galeriacodrel").val(); 
	param += "&accion=1";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_galerias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
	 
			if (msg.IsSucceed)
			{
				GaleriasRelacionadasReload();
				$("#galeriacod").val("");
				$("#galeriafinder").val("");
				$("#msggaleriarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}

function EliminarGaleriaRelacionada(galeriacod)
{
	if (!confirm("Est\u00e1 seguro que desea eliminar la galeria relacionada?"))
		return false;
	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&galeriacod="+galeriacod; 
	param += "&accion=2";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_galerias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				GaleriasRelacionadasReload();
				$("#msggaleriarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}


function DestacarGaleriaRelacionada(tipo,galeriacod)
{
	var accion = 4;
	if (tipo==0)
		accion = 5;

	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&galeriacod="+galeriacod; 
	param += "&accion="+accion;
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_galerias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				GaleriasRelacionadasReload();
				$("#msggaleriarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



function ReordenarGaleriasRelacionadas(orden)
{
	$("#MsgGuardando").show();
	param = "noticiacod="+$("#noticiacod").val(); 
	param += "&galeria="+orden; 
	param += "&accion=3";
	var param, url;
	$.ajax({
	   type: "POST",
	   url: "not_galerias_rel_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
			if (msg.IsSucceed)
			{
				$("#msggaleriarelacionada").html(msg.Msg);
			}else
			{
				alert(msg.Msg);	
			}
			$("#MsgGuardando").hide();
	   }
	});
	
}



function ListarGaleriasRelacionadas(noticiacod)
{
	jQuery("#ListadoGaleriasRelacionadas").jqGrid(
			{ 
				url:'not_galerias_rel_lst_ajax.php?noticiacod='+noticiacod+'&rand='+Math.random(),
				datatype: "json", 
				colNames:['Id', 'Titulo','Fecha','',''], 
				colModel:[ {name:'noticiacod',index:'noticiacod', width:30, sortable:false}, 
						  {name:'galeriatitulo',index:'galeriatitulo', sortable:false}, 
						  {name:'galeriafecha',index:'galeriafecha', width:30, align:"center", sortable:false}, 
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
				emptyrecords: "Sin galerias relacionadas cargadas.",
				loadError : function(xhr,st,err) {
                        alert("Type: "+st+"; Response: "+ xhr.responseText +" "+xhr.statusText + " : "+ err);
						//alert("Error al procesar los datos");
                },
				gridComplete: function() { $("#ListadoGaleriasRelacionadas").addClass("drop"); }
			}); 
			if ($("#puedemodificar").val()==1)
			{
				jQuery("#ListadoGaleriasRelacionadas").jqGrid('sortableRows', 
				 { cursor: 'move',items: '.jqgrow:not(.unsortable)',
				   update : function(e,ui) {
					   var neworder = $("#ListadoGaleriasRelacionadas").jqGrid("getDataIDs");
					   ReordenarGaleriasRelacionadas(neworder);
				   }}
				 );
			}
}

function GaleriasRelacionadasReload(){ 
	var datos = $("#noticiacod").val();
	jQuery("#ListadoGaleriasRelacionadas").jqGrid('setGridParam', {url:"not_galerias_rel_lst_ajax.php?noticiacod="+datos+"rand="+Math.random(),page:1}).trigger("reloadGrid"); 
} 
