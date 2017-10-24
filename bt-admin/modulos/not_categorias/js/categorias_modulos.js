jQuery(document).ready(function(){
	CargarModulodeCategoria();
});



function saveModulo()
{
	switch($("#accionModulo").val())
	{
		case "1":
			GuardarModulo();
			break;
			
		case "2":
			ModificarModulo();
			break;		
	}	
}

function GuardarModulo()
{
	var param, url;
	param = $("#form_tap_modules").serialize();
	param += "&catcod="+$("#catcod").val(); 
	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				$("#PopupModulo").dialog("close");
			}else
			{
				alert(msg.Msg)
			}
	   }
	 });
	
}



function ModificarModulo()
{
	var param, url;
	param = $("#form_tap_modules").serialize();
	param += "&catcod="+$("#catcod").val(); 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				$("#PopupModulo").dialog("close");
				alert("Se ha modificado el m\u00f3dulo correctamente");
				$.unblockUI();
			}else
			{
				alert(msg.Msg);
				$.unblockUI();
			}
	   }
	 });
	
}

function EliminarModulo(catmodulocod)
{
	if (!confirm("Esta seguro que desea eliminar el m\u00f3dulo?"))
		return false;

	$("#sortable").block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Cargando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});   

	var param, url;
	param += "&catmodulocod="+catmodulocod; 
	param += "&catcod="+$("#catcod").val(); 
	param += "&accionModulo=3"; 

	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				$("#PopupModulo").dialog("close");
				$("#modulo_"+catmodulocod).remove();
				$("#sortable").unblock();
			}else
			{
				alert(msg.Msg);
				$("#sortable").unblockUI();
			}
	   }
	 });
	
}


function AbrirEditarModulos(catmodulocod)
{
	var param, url;
	param = "catmodulocod="+catmodulocod;
	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_edit.php",
	   data: param,
	   success: function(msg){
			$("#PopupModulo").dialog({	
				width: 800, 
				height: 'auto',
				resizable: true,
				zIndex: 9999,
				position: 'center', 
				title: "Editar", 
				open: function(type, data) {$("#PopupModulo").html(msg);}
			});
	   }
	 });
	
}

function AbrirAgregarModulos()
{
	if($("#modulocod").val()==""){
		alert("Debe seleccionar un modulo")
		return false;	
	}

	var param, url;
	param = "modulocod="+$("#modulocod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_module_add.php",
	   data: param,
	   success: function(msg){
		   $("#PopupModulo").dialog("close");
			$("#PopupModulo").dialog({	
				width: 800, 
				position: 'top', 
				resizable: true,
				title: "Editar", 
				open: function(type, data) {$("#PopupModulo").html(msg);}
			});
	   }
	 });
	
} 


function CargarModulo(catcod)
{
	var param, url;
	param = "catcod="+catcod;
	$.ajax({
	   type: "POST",
	   url: "tap_tapas_modulo_modules.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#Modulos").html(msg);
	   }
	});
}

function CargarModulodeCategoria()
{
	var param, url;
	param = "catcod="+$("#catcod").val();
	$.ajax({
	   type: "POST",
	   url: "not_categorias_module.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){ 
			$("#sortable").html(msg);
			Sortable();
	   }
	});
}



var position_updated = true; //helper flag for sortable below
function Sortable()
{
	 $("#sortable").sortable({
        connectWith: "#sortable",
        update: function(event, ui) {
			//console.log(ui);
			if (position_updated)
			{	
				var order = $(this).sortable("serialize")+"&catcod="+$("#catcod").val()+"&accionModulo=4"; 
				ModificarOrdenModulos(order);	
			}
        },
		start: function(event, ui) {
			position_updated = true;	
		},
        receive: function(event, ui) {
			position_updated = false;
			AgregarModuloEnOrden(ui.item.attr("id"), $(this).data().sortable.currentItem.index()+1);
        }
    }).disableSelection();
	
}

function ModificarOrdenModulos(param) {
	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
		   if (msg.IsSuccess)
			{
				
			}else
			{
				alert(msg.Msg);
			}
	   }
	});
}

function AgregarModuloEnOrden(id, position) {

	$("#sortable").block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Cargando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});   
	var param, url;
	param = "catcod="+$("#catcod").val()+"&modulocod="+id+"&moduloorden="+position;
	param += "&accionModulo=1"; 

	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
		   if (msg.IsSuccess)
			{
				CargarModulodeCategoria();
			}else
			{
				$("#sortable").html("");
				alert(msg.Msg);
			}
	   }
	});

}


function Droppable()
{
	$( ".draggable li" ).draggable({
		connectToSortable: "#sortable",
		helper: "clone",
		refreshPositions: true,
		drop: function(event, ui) {
		}						
	});

	$(".draggable").disableSelection();
}



function Publicar()
{
	if (!confirm("Esta seguro que desea publicar?"))
		return false;

	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Publicando...</h1>',baseZ: 9999999999 })	

	var param, url;
	param = "catcod="+$("#catcod").val(); 
	param += "&accionModulo=5"; 

	$.ajax({
	   type: "POST",
	   url: "not_categorias_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				alert("Se ha publicado correctamente");
				$.unblockUI();
			}else
			{
				alert(msg.Msg);
				$.unblockUI();
			}
	   }
	 });
	
}

