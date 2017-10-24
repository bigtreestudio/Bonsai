<?php  
abstract class cTapasZonasModulosdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 




	function BuscarModulosxModuloDataxTapa($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_zonas_xmodulodata_xtapacod";
		$sparam=array(
			'ptapacod'=> $datos['tapacod'],
			'pmodulodata'=> $datos['modulodata']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos (data) de la zona.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	function BuscarModulosxZonaxTapa($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_zonas_modulos_xtapacod_xzonacod";
		$sparam=array(
			'ptapacod'=> $datos['tapacod'],
			'pzonacod'=> $datos['zonacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos de la zona.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	function BuscarModuloxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_zonas_modulos_xzonamodulocod";
		$sparam=array(
			'pzonamodulocod'=> $datos['zonamodulocod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulo por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	function BuscarModuloxCodigoModulo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_modulos_xmodulocod";
		$sparam=array(
			'pmodulocod'=> $datos['modulocod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulo por codigo de modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	function Modificar($datos)
	{
		$spnombre="upd_tap_tapas_zonas_modulos_xzonamodulocod";
		$sparam=array(
			'pmodulodata'=> $datos['modulodata'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pzonamodulocod'=> $datos['zonamodulocod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	function Eliminar($datos)
	{
		$spnombre="del_tap_tapas_zonas_modulos_xzonamodulocod";
		$sparam=array(
			'pzonamodulocod'=> $datos['zonamodulocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	
	function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_tap_tapas_zonas_modulos";
		$sparam=array(
			'ptapacod'=> $datos['tapacod'],
			'pzonacod'=> $datos['zonacod'],
			'pmodulocod'=> $datos['modulocod'],
			'pmodulodata'=> $datos['modulodata'],
			'pmoduloorden'=> $datos['moduloorden'],
			'pwidth'=> $datos['width'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;	
	}


	function ModificarOrdenZona($datos)
	{
		$spnombre="upd_tap_tapas_zonas_modulos_xzonamodulocod_tapacod";
		$sparam=array(
			'pzonacod'=> $datos['zonacod'],
			'pmoduloorden'=> $datos['moduloorden'],
			'pwidth'=> $datos['width'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pzonamodulocod'=> $datos['zonamodulocod'],
			'ptapacod'=> $datos['tapacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden y la zona del modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}



	function ModificarBloqueoZona($datos)
	{
		$spnombre="upd_tap_tapas_zonas_modulos_modulobloqueado_xzonamodulocod";
		$sparam=array(
			'pmodulobloqueado'=> $datos['modulobloqueado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pzonamodulocod'=> $datos['zonamodulocod'],
			'ptapacod'=> $datos['tapacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el bloqueo del modulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}



}

?>