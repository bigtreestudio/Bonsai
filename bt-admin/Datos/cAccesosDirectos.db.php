<?php  
abstract class cAccesosDirectosdb
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

//----------------------------------------------------------------------------------------- 
// Retorna los accesos directos de las tapas por tipoacceso

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no


	protected function BuscarAccesosDirectosxTipoacceso($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_acc_acceso_directo_xtipoacceso";
		$sparam=array(
			'ptipoacceso'=> $datos['tipoacceso']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el acceso directo por tipo de acceso.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	}

	protected function BuscarAccesosDirectos($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_moudulos_accesos_directos";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el acceso directo por tipo de acceso.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	}

/*
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_paises_busqueda";
		$sparam=array(
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los paises.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}


	protected function StorePaisesxEstado($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_paises_xpaisestado";
		$sparam=array(
			'ppaisestado'=> $datos['paisestado'],
			'porderby'=> "paisdesc"
			);		
		return true;	
	}


	protected function BuscarPaisesActivos(&$resultado,&$numfilas)
	{
		$datos['paisestado'] = ACTIVO;
		$this->StorePaisesxEstado($datos,$spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los paises activas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}
	
	
	protected function BuscarPaisxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_paises_xpaiscod";
		$sparam=array(
			'ppaiscod'=> $datos['paiscod']
			);	
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el país por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	}
	
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_paises";
		$sparam=array(
			'ppaisdesc'=> $datos['paisdesc'],
			'ppaisestado'=> NOACTIVO,
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el país.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

	
		$spnombre="upd_paises_xpaiscod";
		$sparam=array(
			'ppaisdesc'=> $datos['paisdesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppaiscod'=> $datos['paiscod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el país.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




	protected function ActivarDesactivar($datos)
	{
		
		$spnombre="upd_paises_activar_desactivar_xpaiscod";
		$sparam=array(
			'ppaisestado'=> $datos['paisestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppaiscod'=> $datos['paiscod']
		);	
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar / desactivar el país.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
	
	
	protected function Eliminar($datos)
	{

		$spnombre="del_paises_xpaiscod";
		$sparam=array(
			'ppaiscod'=> $datos['paiscod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el país.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	*/

}


?>