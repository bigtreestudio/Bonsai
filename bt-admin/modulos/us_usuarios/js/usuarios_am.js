jQuery(document).ready(function(){
	CargarDatosPersonales();
});
function Validar(formulario)
{
	if (!ValidarContenido(formulario.usuarioemail.value,"Email"))
	{
		alert("Debe ingresar un email v\u00e1lido");
		formulario.usuarioemail.focus();
		return false;	
	}
	if(formulario.usuarioemail.value=="")
	{
		alert("Debe ingresar un email");
		formulario.usuarioemail.focus();
		return false;	
	}
	if(formulario.usuarionombre.value=="")
	{
		alert("Debe ingresar un nombre");
		formulario.usuarionombre.focus();
		return false;	
	}
	if(formulario.usuarioapellido.value=="")
	{
		alert("Debe ingresar un apellido");
		formulario.usuarioapellido.focus();
		return false;	
	}
	/*
	if (!ValidarContenido(formulario.usuariodoc.value,"NumericoEntero"))
	{
		alert("Debe ingresar un n\u00famero de documento v\u00e1lido");
		formulario.usuariodoc.focus();
		return false;	
	}
	if(formulario.usuariodoc.value=="")
	{
		alert("Debe ingresar un n\u00famero de documento");
		formulario.usuariodoc.focus();
		return false;	
	}

	if ($("#usuariosexo_F").prop('checked')!=true && $("#usuariosexo_M").prop('checked')!=true)
	{	
		alert("Debe seleccionar el sexo del usuario");
		return false;	
	}


	if(formulario.usuariofnacimiento.value=="")
	{
		alert("Debe ingresar una fecha de nacimiento");
		formulario.usuariofnacimiento.focus();
		return false;	
	}
	if (!ValidarFecha(formulario.usuariofnacimiento.value))
	{
		alert("La fecha de nacimiento debe ser v\u00e1lida");
		formulario.usuariofnacimiento.focus();
		return false;	
	}

	if(formulario.usuariosexo.value=="")
	{
		alert("Debe ingresar tipo de sexo");
		formulario.usuariosexo.focus();
		return false;	
	}

	if(formulario.provinciacod.value=="")
	{
		alert("Debe seleccionar una provincia de residencia del usuario");
		formulario.provinciacod.focus();
		return false;	
	}
	
	if(formulario.provinciacod.value!="")
	{
		if(formulario.departamentocod.value=="")
			{	
				alert("Debe seleccionar una ciudad/localidad de residencia del usuario");
				formulario.localidadcod.focus();
				return false;
			}
	}

*/

	if(formulario.modif.value==0)
	{		
		if (formulario.usuariopassword.value=="")
		{
			alert("Debe ingresar una contrase\u00f1a");
			formulario.usuariopassword.focus();
			return false;	
		}
		if (!ValidarPassword(formulario.usuariopassword.value,'',formulario.usuarioemail.value,8))
		{
			alert("La contrase\u00f1a debe tener al menos 8 caracteres");
			formulario.usuariopassword.focus();
			return false;	
		}
		if (formulario.usuariopassword.value!=formulario.usuariopasswordconfirm.value)
		{
			alert("La contrase\u00f1a debe ser igual a la confirmaci\u00f3n");
			formulario.usuariopasswordconfirm.focus();
			return false;	
		}
	}
	


	
	var arregloroles = new Array;
	var chk = false;
	$(".chk").each(function(){
		if (this.checked==true)
			chk=true;
	})
	
	if (!chk)
	{
		alert("Debe seleccionar al menos un rol.");
		return false;
	}




	return true;
}

function BuscarUsuarioEmail()
{
	var param, url;
	if (!ValidarContenido($("#usuarioemail").val(),"Email")){ $("#ChkEmail").html("<img src='images/icon_error.png' title='Mail Invalido' />Mail invalido");	return false}
	if ($("#usuarioemail").val()==mailusuario){$("#ChkEmail").html("<img src='images/icon_accept.png' title='Mail Valido' />Mail invalido");return true;}
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


function AutocompleteUsuario()
{
	$("#jefefinder").val();
	jQuery("#jefefinder").autocomplete({
		minLength: 3,
		delay : 400,
		source: function(request, response) {
			jQuery.ajax({
			   url:      "usuarios_busqueda_autocomplete.php",
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
			var usuariocod= ui.item.usuariocod;
			$("#jefecod").val(usuariocod);
		}
	});
}

function CargarPagina(url,param,id){
	$("#cargando").show();
	$.ajax({
		type: "POST", 
		url: url,
		data: param, 
		success: function(msg){
			$("#cargando").hide();
			$(id).html(msg);
		}});
	return true;
}

function CargarDatosPersonales(borramsg)
{
	var param = "usuariocod="+usuariocod;//new Array();	
	CargarPagina("usuarios_datos_personales.php",param,"#DatosPersonales")
	if (borramsg)
		$("#MsgAccionDatos").html("");
}
