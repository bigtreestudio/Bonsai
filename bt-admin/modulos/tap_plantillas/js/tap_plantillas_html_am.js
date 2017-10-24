

jQuery(document).ready(function(){
}); 


//FUNCION QUE LLAMA AL UPD E INSERTA TODOS LOS DATOS TRAIDOS DEL FORMULARIO

function Insertar()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Agregando plantilla...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	$(".msgaccionhtml").html("&nbsp;");
	var param, url;
	param = $("#formulario").serialize();
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_html_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				$(".msgaccionhtml").html(msg.Msg);
				document.location.href="tap_plantillas_html_am.php?planthtmlcod="+msg.planthtmlcod+"&md5="+msg.md5recarga;
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


//FUNCION QUE LLAMA AL UPD Y MODIFICA TODOS LOS DATOS TRAIDOS DEL FORMULARIO

function Actualizar()
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Actualizando plantilla...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	var param, url;
	param = $("#formulario").serialize();
	$(".msgaccionhtml").html("&nbsp;");
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_html_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){			
			if (msg.IsSuccess==true)
			{
				$(".msgaccionhtml").html(msg.Msg);
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

//FUNCION QUE LLAMA AL UPD Y ELIMINA LKA PLANTILLA HTML
//DATOS DE ENTRADA:
//      ACCION= ACCION QUE REALIZA (BAJA)
//      PLANTHTMLCOD= CODIGO DE LA PLANTILLA A ELIMINAR


function Eliminar()
{
	if (!confirm("Esta seguro que desea eliminar la plantilla html?"))
		return false;
		
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando plantilla...</h1>',baseZ: 9999999999 })	
	$("#MsgGuardando").show();
	$(".msgaccionhtml").html("&nbsp;");
	var param, url;
	param='accion=3&planthtmlcod='+$("#planthtmlcod").val();
	$.ajax({
	   type: "POST",
	   url: "tap_plantillas_html_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
			if (msg.IsSuccess==true)
			{
				document.location.href="tap_plantillas_html.php";
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


