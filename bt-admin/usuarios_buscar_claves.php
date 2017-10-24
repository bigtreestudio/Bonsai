<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oUsuarios = new cUsuarios($conexion);

$ok = 1;
switch ($_POST['tipo'])
{
	case 1:
		$numfilas = 1;
		if (!isset($_POST['usuarioid']) || $_POST['usuarioid']!="")
		{
			$datos['usuarioid'] = $_POST['usuarioid'];
			if (!$oUsuarios->BuscarUsuariosXIdUsuario($datos,$numfilas,$resultado))
				$ok = 0;
		}
		if ($ok && $numfilas != 0)
			$ok = 0;

		echo $ok;
		break;

	case 2:
		$numfilas = 0;
		if (!isset($_POST['usuarioemail']) || ($_POST['usuarioemail']==""))
			$ok=0;
		elseif (!FuncionesPHPLocal::ValidarContenido($conexion,$_POST['usuarioemail'],"Email"))
			$ok = 0;
		else
		{
			$datos['usuarioemail'] = $_POST['usuarioemail'];
			if (!$oUsuarios->BuscarUsuarioxMail($datos,$resultado,$numfilas))
				$ok = 0;
			if ($ok==0 || $numfilas != 0)
				$ok = 0;
		}

		echo $ok;
		break;
	case 3: //VALIDO EL DNI DE USUARIO
		$numfilas = 0;
		if (!isset($_POST['tipodocumentocod']) || !isset($_POST['usuariodoc']) || !isset($_POST['usuariosexo']))
		{
			$ok = 0;
		}
		else
		{
			$datos['usuariodoc'] = $_POST['usuariodoc'];
			$datos['usuariosexo'] = $_POST['usuariosexo'];
			$datos['tipodocumentocod'] = $_POST['tipodocumentocod'];
			
			if (!$oUsuarios->BuscarUsuarioxDocumento($datos,$resultado,$numfilas))
				$ok = 0;
			
			if ($ok==0 || $numfilas != 0)
			{
				$ok = 0;
			}
		}

		echo $ok;
		break;
	case 4:
		$numfilas = 0;
		if (isset($_POST['medicomatricula']) && isset($_POST['tipomatriculacod']))
		{
			//este metodo hay que moverlo a cMedicos
			$datos['medicomatricula'] = $_POST['medicomatricula'];
			$datos['tipomatriculacod'] = $_POST['tipomatriculacod'];
			if (!$oUsuarios->BuscarUsuarioxMatricula($datos,$resultado,$numfilas))
				$ok = 0;
		}
		if ($ok==0 || $numfilas != 0)
		{
			$ok = 0;
		}

		echo $ok;
		break;
		
	case 5: //VALIDO EL DNI DE USUARIO y DEVUELVO EL USUARIOCOD SI LO ENCUENTRO
		
		$ok=0; //USUARIO DISPONIBLE
		$numfilas = 0;
		if (!isset($_POST['tipodocumentocod']) || !isset($_POST['usuariodoc']) || !isset($_POST['usuariosexo']))
		{
			$ok = -1; //FALTAN DATOS
		}
		else
		{
			$datos['usuariodoc'] = $_POST['usuariodoc'];
			$datos['usuariosexo'] = $_POST['usuariosexo'];
			$datos['tipodocumentocod'] = $_POST['tipodocumentocod'];
			
			if (!$oUsuarios->BuscarUsuarioxDocumento($datos,$resultado,$numfilas))
				$ok = -1; //ERROR EN BUSQUEDA
			
			if ($numfilas != 0)
			{
				$filaUsuario=$conexion->ObtenerSiguienteRegistro($resultado);
				$ok = $filaUsuario["usuariocod"];	
			}
		}

		echo $ok;
		break;
	
	case 6:
		
		if (!isset($_POST['usuarioemail']) || ($_POST['usuarioemail']==""))
			$ok=0;
		elseif (!FuncionesPHPLocal::ValidarContenido($conexion,$_POST['usuarioemail'],"Email"))
			$ok = 0;
		else
		{
			$datos['usuarioemail'] = $_POST['usuarioemail'];
			if (!$oUsuarios->BuscarUsuarioxMail($datos,$resultado,$numfilas))
				$ok = 0;
			else
			{
				if ($numfilas!=0)
				{
					$datosusuario = $conexion->ObtenerSiguienteRegistro($resultado);
					if ($datosusuario['usuariocod']!=$_POST['usuariocod'])
						$ok = 0;
				}	
			}	
		}

		echo $ok;
		break;
}



?>