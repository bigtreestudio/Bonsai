<?php  
abstract class cCategoriasModulosdb
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
	
	protected function BuscarxCategoria($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_categorias_modulos_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los módulos de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscarModuloxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_categorias_modulos_xcatmodulocod";
		$sparam=array(
			'pcatmodulocod'=> $datos['catmodulocod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el módulo de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscarModuloCategoriaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_categorias_modulos_moduloordenmax";
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
		$spnombre="ins_not_categorias_modulos";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pmodulocod'=> $datos['modulocod'],
			'pmoduloorden'=> $datos['moduloorden'],
			'pmodulodata'=> $datos['modulodata'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un modulo en una categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_not_categorias_modulos_xcatmodulocod";
		$sparam=array(
			'pmodulodata'=> $datos['modulodata'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatmodulocod'=> $datos['catmodulocod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el modulo de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	
	protected function Eliminar($datos)
	{

		$spnombre="del_not_categorias_modulos_xcatmodulocod";
		$sparam=array(
			'pcatmodulocod'=> $datos['catmodulocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo de la categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	protected function EliminarxCategoria($datos)
	{
		$spnombre="del_not_categorias_modulos_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el modulo categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}	

	protected function ModificarOrden($datos)
	{
		$spnombre="upd_not_categorias_modulos_xmoduloorden";
		$sparam=array(
			'pmoduloorden'=> $datos['moduloorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcatmodulocod'=> $datos['catmodulocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de los modulos de las categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}

}


?>