
	
function EnviarDatos(param,accion)
{
	$.ajax({
	   type: "POST",
	   url: "con_contactos_upd.php",
	   data: param,
	   dataType:"json",
	   success: function(msg){
		if (msg.IsSuccess==true)
		{
			switch(accion)
			{
				case 1:
					document.location.href="con_contactos_am.php?formulariocod="+msg.formulariocod;
					break;	
				case 2:
					alert(msg.Msg);
					break;	
				
				case 3:
					document.location.href="con_contactos.php";
					break;	
				
			}
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

function EliminarFormContacto(formulariocod)
{
	
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Eliminando Formulario...</h1>',baseZ: 9999999999 })	
	var param;
	if (!confirm("Est\u00e1 seguro que desea eliminar el formulario de contacto?"))
		return false;
	param = "formulariocod="+formulariocod;
	param += "&accion=3";
	EnviarDatos(param,3);

	return true;
}


function InsertarFormContacto()
{
	var param;
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Agregando Formulario...</h1>',baseZ: 9999999999 })	
	param = $("#formulario").serialize();
	param += "&accion=1";
	EnviarDatos(param,1);
	
	return true;
}


function ModificarFormContacto(formulariocod)
{
	$.blockUI({ message: '<h1 class="h1block"><img src="images/cargando.gif" /> Modificando Formulario...</h1>',baseZ: 9999999999 })	

	param = $("#formulario").serialize();
	param += "&accion=2";
	EnviarDatos(param,2);
	
	return true;
}