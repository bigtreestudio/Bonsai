<?php 
/* La clase requiere que esten definidos:

	Stored Básicos: sel_sessions_xsessionid
					ins_sessions
					upd_sessions_xsessionid
					del_sessions_xsessionid
					del_sessions_xmayortiempo
					sel_roles_modulos_xrolcod_xarchivonom

	Registros de Acceso: 
*/
class Sesion
{
	var $conexionsesion;
	
	function Sesion($conexion,$inicializa=false,$id=false)
	{
		$this->conexionsesion = &$conexion;
		
		session_set_save_handler(
			array(& $this, 'sessao_open'), 
			array(& $this, 'sessao_close'), 
			array(& $this, 'sessao_read'), 
			array(& $this, 'sessao_write'), 
			array(& $this, 'sessao_destroy'), 
			array(& $this, 'sessao_gc')
		);
		
		if($id!==false)
			session_id($id);
		
		session_start();

		if($inicializa || !isset($_SESSION['rolcod']) || !isset($_SESSION['usuariocod']) )
		{
			$_SESSION=array();
			$_SESSION['rolcod']=0;
			$_SESSION['usuariocod']=0;
			$_SESSION['usuarionombre']=0;
			$_SESSION['usuarioapellido']=0;
			$_SESSION['usuarioid']=0;
		}
	}

	function sessao_open($aSavaPath, $aSessionName)
	{
	   $this->sessao_gc(TIEMPOSESION);
	   return true;
	}
	
	function sessao_close()
	{
		return true;
	}
	
	function sessao_read($aKey)
	{
		$spparam=array('pkey'=>$aKey);
		if(!$this->conexionsesion->ejecutarStoredProcedure("sel_sessions_xsessionid",$spparam,$resultado,$numfilas,$errno))
			die("Error al acceder a sessions - ".$errno);
		
		if($numfilas == 1)
		{
			$r = $this->conexionsesion->ObtenerSiguienteRegistro($resultado);
			// OJO cambiar si se cambia el ejecutar stored_procedures
			return $r['DataValue'];
		} 
		else
		{
			$spparam=array('pkey'=>$aKey);
			if(!$this->conexionsesion->ejecutarStoredProcedure("ins_sessions",$spparam,$resultado,$numfilas,$errno))
				die("Error al acceder a sessions - ".$errno);
	
			return "";
		}
	}
	
	function sessao_write( $aKey, $aVal )
	{
		$spparam=array('pkey'=>$aKey,'pdata'=>$aVal);
		if(!$this->conexionsesion->ejecutarStoredProcedure("upd_sessions_xsessionid",$spparam,$resultado,$numfilas,$errno))
			die("Error al acceder a sessions - ".$errno);
	
		return true;
	}
	
	function sessao_destroy( $aKey )
	{
		$spparam=array('pkey'=>$aKey);
		if(!$this->conexionsesion->ejecutarStoredProcedure("del_sessions_xsessionid",$spparam,$resultado,$numfilas,$errno))
			die("Error al acceder a sessions - ".$errno);
	
		return true;
	}
	
	function sessao_gc( $aMaxLifeTime )
	{
		$spparam=array('ptiempolimite'=>$aMaxLifeTime);
		if(!$this->conexionsesion->ejecutarStoredProcedure("del_sessions_xmayortiempo",$spparam,$resultado,$numfilas,$errno))
			die("Error al acceder a sessions - ".$errno);
	
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Verifica si tiene los permisos suficientes para acceder a un determinado archivo

	function TienePermisos($conexion,$usuariocod,$rolcod,$archivonom)
	{
		$_POST = FuncionesPHPLocal::RemoveMagicQuotes ($_POST, true);
		$archivonom=substr(strrchr($archivonom,'/'),1);
		if ($rolcod==0 || $usuariocod==0)
		{
			FuncionesPHPLocal::RegistrarAcceso($conexion,'010002','',$usuariocod);
			//die ('Ud. tiene que registrarse para poder acceder.');
			header("location:index.php?msg_error=2");
			die();
		}
		elseif(!isset($_SESSION['sistema']) || $_SESSION['sistema']!=SISTEMA)
		{
			FuncionesPHPLocal::RegistrarAcceso($conexion,'010005',(isset($_SESSION['sistema'])?$_SESSION['sistema']:"")." - ".$usuariocod,0);
			die ('Ud. no tiene los permisos suficientes para ingresar a la página solicitada.');
		}
		 else 	{
		
			$spnombrerol='sel_roles_modulos_xrolcod_xarchivonom';
			$spparamrol=array('prolcod'=>$rolcod,'parchivonom'=>$archivonom);
			if(!$conexion->ejecutarStoredProcedure($spnombrerol,$spparamrol,$resultado,$numfilas,$errno) || $numfilas<1)
			{
				FuncionesPHPLocal::RegistrarAcceso($conexion,'010003','',$usuariocod);
				die ('Ud. no tiene los permisos suficientes para ingresar a la página solicitada.');
			}
			$datosarch = $this->conexionsesion->ObtenerSiguienteRegistro($resultado);
			$_SESSION['modcod']=$datosarch['grupomodcod'];
			if ($datosarch['modulocod']!=202)
				$_SESSION['modulocodsel'] = $datosarch['modulocod'];
			FuncionesPHPLocal::RegistrarAcceso($conexion,'010001','',$usuariocod);
		}	
	}
}
?>