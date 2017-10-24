<?php  
abstract class cTemasdb
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
// Retorna el SP y los parametros para cargar los roles del sistema

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	/*protected function CategoriasSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_temas";
		$sparam=array(
			);
		return true;
	}*/
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_xtemacod";
		$sparam=array(
			'ptemacod'=> $datos['temacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}

	protected function BuscarTemasxTemaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_xtemacodsuperior";
		$sparam=array(
			'ptemacodsuperior'=> $datos['temacodsuperior']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el tema por tema superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarAvanzadaxTemaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_busqueda_xtemasupnull";
		$sparam=array(
			'pxtemacodsuperior'=> $datos['xtemacodsuperior'],
			'pxtemacodsuperior1'=> $datos['xtemacodsuperior1'],
			'ptemacodsuperior1'=> $datos['temacodsuperior1'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el tema por tema superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function BuscaTemasRaiz(&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_xtemasupnull";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el tema por tema superior. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function BuscaTemasNombreRaiz($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_xtematitulo_xtemasupnull";
		$sparam=array(
			'ptematitulo'=> $datos['tematitulo']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tema por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscaTemasNombrexTemaSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tem_temas_xtematitulo_temacodsuperior";
		$sparam=array(
			'ptematitulo'=> $datos['tematitulo'],
			'ptemacodsuperior'=> $datos['temacodsuperior']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tema por nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_tem_temas";
		$sparam=array(
			'ptemacodsuperior'=> $datos['temacodsuperior'],
			'ptematitulo'=> $datos['tematitulo'],
			'ptemadesc'=> $datos['temadesc'],
			'ptemacolor'=> $datos['temacolor'],
			'ptemaestado'=> $datos['temaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_tem_temas_xtemacod";
		$sparam=array(
			'ptematitulo'=> $datos['tematitulo'],
			'ptemadesc'=> $datos['temadesc'],
			'ptemacolor'=> $datos['temacolor'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptemacod'=> $datos['temacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function ModificarEstadoTema($datos)
	{
		$spnombre="upd_tem_temas_xtemaestado";
		$sparam=array(
			'ptemaestado'=> $datos['temaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptemacod'=> $datos['temacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de el tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	
	protected function Eliminar($datos)
	{

		$spnombre="del_tem_temas_xtemacod";
		$sparam=array(
			'ptemacod'=> $datos['temacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

}


?>