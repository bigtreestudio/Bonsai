<?php  
abstract class cPaginasModulosdb
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
// Retorna el resultado con los modulos de la pagina

// Parámetros de Entrada:
//		$datos = array con datos 
//			pagcod = codigo de la pagina.
// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarxPagina($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_modulos_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los módulos de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscarModuloxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_modulos_modulos";
		$sparam=array(
			'ppagmodulocod'=> $datos['pagmodulocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los módulos de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscarModuloPaginaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_modulos_moduloordenmax";
		$sparam=array(
			
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_pag_paginas_modulos";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
			'pmodulocod'=> $datos['modulocod'],
			'pmoduloorden'=> $datos['moduloorden'],
			'pmodulodata'=> $datos['modulodata'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un modulo en una pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_pag_paginas_modulos_xpagmodulocod";
		$sparam=array(
			'pmodulodata'=> $datos['modulodata'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagmodulocod'=> $datos['pagmodulocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el modulo de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	
	protected function Eliminar($datos)
	{

		$spnombre="del_pag_paginas_modulos_xpagmodulocod";
		$sparam=array(
			'ppagmodulocod'=> $datos['pagmodulocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	protected function EliminarxPagina($datos)
	{

		$spnombre="del_pag_paginas_modulos_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo de la pagina por pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}	

	protected function ModificarOrden($datos)
	{
		$spnombre="upd_pag_paginas_modulos_xmoduloorden";
		$sparam=array(
			'pmoduloorden'=> $datos['moduloorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagmodulocod'=> $datos['pagmodulocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de los modulos de las pagians. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}

}


?>