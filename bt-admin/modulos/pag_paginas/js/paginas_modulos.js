jQuery(document).ready(function(){
	CargarModulodePagina();
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
	param += "&pagcod="+$("#pagcod").val(); 
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_module_upd.php",
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
	param += "&pagcod="+$("#pagcod").val(); 
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando datos...</h1>',baseZ: 9999999999 })	
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_module_upd.php",
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

function EliminarModulo(pagmodulocod)
{
	if (!confirm("Esta seguro que desea eliminar el m\u00f3dulo?"))
		return false;

	$("#sortable").block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Cargando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});   

	var param, url;
	param += "&pagmodulocod="+pagmodulocod; 
	param += "&pagcod="+$("#pagcod").val(); 
	param += "&accionModulo=3"; 

	$.ajax({
	   type: "POST",
	   url: "pag_paginas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				$("#PopupModulo").dialog("close");
				$("#modulo_"+pagmodulocod).remove();
				$("#sortable").unblock();
			}else
			{
				alert(msg.Msg);
				$("#sortable").unblockUI();
			}
	   }
	 });
	
}


function AbrirEditarModulos(pagmodulocod)
{
	var param, url;
	param = "pagmodulocod="+pagmodulocod;
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_module_edit.php",
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

function CargarModulodePagina()
{
	var param, url;
	param = "pagcod="+$("#pagcod").val();
	$.ajax({
	   type: "POST",
	   url: "pag_paginas_module.php",
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
				var order = $(this).sortable("serialize")+"&pagcod="+$("#pagcod").val()+"&accionModulo=4"; 
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
	   url: "pag_paginas_module_upd.php",
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

	if($("#pagcod").val()==""){
		$("#sortable").html("");
		alert("Debe guardar la pagina para asociar m\u00f3dulos")
		return false;	
	}

	$("#sortable").block({ 
		message: '<div style="text-align:left; font-weight:bold; background:url(images/cargando.gif) no-repeat left; padding:5px 0 5px 20px">Cargando</div>', 
		css: { border: '1px solid #202020', padding:'0 5px', width: '110px'  } 
	});   
	var param, url;
	param = "pagcod="+$("#pagcod").val()+"&modulocod="+id+"&moduloorden="+position;
	param += "&accionModulo=1"; 

	$.ajax({
	   type: "POST",
	   url: "pag_paginas_module_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){ 
		   if (msg.IsSuccess)
			{
				CargarModulodePagina();
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

