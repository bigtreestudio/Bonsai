// JavaScript Document
//CARGA INICIAL UNA VEZ QUE TERMINE DE CARGAR EL DOCUMENTO
jQuery(document).ready(function(){
	MovimientoMenus();
});

function MovimientoMenus()
{	
	$('.sortable').nestedSortable({
		disableNesting: 'no-nest',
		forcePlaceholderSize: true,
		handle: '.menuhandle',
		helper:	'clone',
		items: 'li',
		maxLevels: 2,
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		update: function () {
       		order = $('ol.sortable').nestedSortable('serialize')+"&menutipocod="+$("#menutipocod").val()+"&accion=4";
			$.post("tap_menu_upd.php", order, function(msg){
				if (msg.IsSuccess)
				{
					
				}else
				{
					alert(msg.Msg)	
				}
			}, "json");
    }

	});
}



function publicarMenu()
{
	if (!confirm("Esta seguro que desea publicar el men\u00fa?"))
		return false;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Publicando men\u00fa...</h1>',baseZ: 9999999999999 })	
	var param, url;
	param = "menutipocod="+$("#menutipocod").val();
	param += "&accion=5"; 
	$.ajax({
	   type: "POST",
	   url: "tap_menu_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		   if (msg.IsSuccess)
			{
				alert(msg.Msg);
				$.unblockUI();	
			}else
			{
				alert(msg.Msg)
				$.unblockUI();	
			}
	   }
	 });
	
}



function EditarMenu(menucod)
{
	var param, url;
	param = "menucod="+menucod;
	$.ajax({
	   type: "POST",
	   url: "tap_menu_am.php",
	   data: param,
	   success: function(msg){
			$("#PopupDetalleMenu").dialog({	
				width: 800, 
				zIndex: 999999,
				height: 'auto',
				position: 'top', 
				resizable: true,
				title: "Editar", 
				open: function(type, data) {$("#PopupDetalleMenu").html(msg);}
			});
	   }
	 });
	
}

function AgregarMenu()
{
	var param, url;
	param = "menutipocod="+$("#menutipocod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_menu_am.php",
	   data: param,
	   success: function(msg){
			$("#PopupDetalleMenu").dialog({	
				width: 800, 
				height: 'auto',
				zIndex: 999999,
				position: 'center', 
				resizable: true,
				title: "Editar", 
				open: function(type, data) {$("#PopupDetalleMenu").html(msg);}
			});
	   }
	 });
	
}


function DialogClose()
{
	$("#PopupDetalleMenu").dialog("close");
}



function InsertarMenu()
{
	param = $("#formmenuam").serialize();
	param += "&accion=1";
	EjecutarAccionMenu(param);
	return true;
}

function ModificarMenu()
{
	param = $("#formmenuam").serialize();
	param += "&accion=2";
	EjecutarAccionMenu(param);
	return true;
}

function EliminarMenu(menucod)
{
	if (!confirm("Esta seguro que desea elimnar el men\u00fa?"))
		return false;
	var param, url;
	param = "menucod="+menucod;
	param += "&accion=3";
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Eliminando men\u00fa...</h1>',baseZ: 9999999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_menu_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			RecargarMenu();
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
	return true;
}


function RecargarMenu()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Cargando men\u00fa...</h1>',baseZ: 9999999999999 })	
	param = "menutipocod="+$("#menutipocod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_menu_recarga.php",
	   data: param,
	   dataType:"html",
	   success: function(msg){
			$("#MenuCarga").html(msg);
			MovimientoMenus();
			$.unblockUI();	
	   }
	 });
}

function EjecutarAccionMenu(param)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" />Guardando datos...</h1>',baseZ: 9999999999999 })	
	$.ajax({
	   type: "POST",
	   url: "tap_menu_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			RecargarMenu();
			alert(msg.Msg);
			$("#PopupDetalleMenu").dialog("close"); 
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


function DesactivarTextoLink() {


	if (checklink.checked==true)
	{
		$("#menulink").prop("disabled",true);
		$("#menulink").val("javascript:void(0);");
	}else
	{
		$("#menulink").prop("disabled",false);
		$("#menulink").val("");
		
	}
}






