<?
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
		if(!isset($_SESSION))
			session_start();

		if($inicializa || (!isset($_SESSION['clientecod'])) )
		{
			//$_SESSION=array();
			//$_SESSION['clientecod']=0;
			//$_SESSION['nombrecompleto']="";
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

	function EstoyLogueado()
	{
		if (!isset($_SESSION['clientecod']) || $_SESSION['clientecod']==0)
		{
			return false;
		}
		return true;
	}

	function SoyUsuarioAnonimo()
	{
		if (!isset($_SESSION['clienteanonimo']) || $_SESSION['clienteanonimo']!=1)
			return false;	
		else
			return true;
	}


}
?>