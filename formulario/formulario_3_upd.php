<?php 

/*LA FUNCION DEBE LLAMARSE VALIDARDATOSPARTICULARES PARA TODOS LOS FORMULARIOS*/
function ValidarDatosParticulares($datos,&$arreglodatosjson)
{
	include("formulario_3_data.php");
	if (!isset($datos['formulariotelefono']) || $datos['formulariotelefono']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un telefono.";
		$_SESSION['error'] = 9;
		return false;	
	}
	if (!isset($datos['Tema']) || $datos['Tema']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un tema.";
		$_SESSION['error'] = 10;
		return false;	
	}
	if (!array_key_exists($datos['Tema'],$arregloTema))
	{
		$_SESSION['accionmsg'] = "Debe ingresar un tema.";
		$_SESSION['error'] = 10;
		return false;	
	}
	if (!isset($datos['Organismo']) || $datos['Organismo']=="")
	{
		$_SESSION['accionmsg'] = "Debe ingresar un organismo.";
		$_SESSION['error'] = 11;
		return false;	
	}

	if (!array_key_exists($datos['Organismo'],$arregloOrganismo))
	{
		$_SESSION['accionmsg'] = "Debe ingresar un organismo valido.";
		$_SESSION['error'] = 11;
		return false;	
	}
	if ($datos['Organismo']=="99999")
	{
		if (!isset($datos['OtroOrganismo']) || $datos['OtroOrganismo']=="")
		{
			$_SESSION['accionmsg'] = "Debe ingresar un nombre de organismo.";
			$_SESSION['error'] = 12;
			return false;	
		}
	}else
	{
		$datos['OtroOrganismo'] = "---";
	}
	$arreglodatosjson['Tel&eacute;fono'] = $datos['formulariotelefono'];
	$arreglodatosjson['Tema'] = $arregloTema[$datos['Tema']];
	$arreglodatosjson['Organismo'] = $arregloOrganismo[$datos['Organismo']];
	$arreglodatosjson['Otro Organismo'] = $datos['OtroOrganismo'];

	return true;
}
?>