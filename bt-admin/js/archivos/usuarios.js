$(document).ajaxStart($.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Cargando mis datos...</h1>',baseZ: 9999999999 })).ajaxStop($.unblockUI);

function CargarPagina(url,param,id){$("#cargando").show(); $.ajax({ type: "POST", url: url, data: param, success: function(msg){$("#cargando").hide(); $(id).html(msg);}});return true;}
function CargarDatosPersonales(borramsg)
{
	var param = "usuariocod="+usuariocod;//new Array();	
	CargarPagina("usuarios_datos_personales.php",param,"#DatosPersonales")
	if (borramsg)
		$("#MsgAccionDatos").html("");
}
function AccionModificarDatos(url,param,id)
{
	
	$("#cargando").show();
	$(id).html('');
	$.ajax({type: "POST", url: url, data: param, dataType:"json",
	   success: function(msg){
		   	//alert(msg);
		   	if (msg.IsSuccess==true){$(id).html(msg.Msg);}else{alert(msg.Msg);}
		 	$("#cargando").hide();
	   }
	 });
	return true;
}
function ModificarDatosPersonales()
{
	var param = new Array();
	param = $("#formulario").serialize();
	//alert(param);
	AccionModificarDatos("usuarios_modificar_upd.php",param,"#MsgAccionDatos")
}

function BuscarUsuarioEmail()
{
	var param, url;
	if (!ValidarContenido($("#usuarioemail").val(),"Email")){ $("#ChkEmail").html("<img src='images/icon_error.png' title='Mail Invalido' />Mail valido");	return false}
	if ($("#usuarioemail").val()==mailusuario){$("#ChkEmail").html("<img src='images/icon_error.png' title='Mail Valido' />Mail invalido");return true;}
	else{
		$("#ChkEmail").html("&nbsp;");
		param = "usuarioemail="+$("#usuarioemail").val()+"&tipo=2";
		$.ajax({type: "POST", url: "usuarios_buscar_claves.php", data: param, success: function(msg){
				if (msg==1)
				{$("#ChkEmail").html("<img src='images/icon_accept.png' title='Mail Valido' />Mail valido");	return true;}
				else{$("#ChkEmail").html("<img src='images/icon_error.png' title='Mail Invalido' />Mail invalido");	return false;}
		}});
	}	 
}



