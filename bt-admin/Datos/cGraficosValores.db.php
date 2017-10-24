<?php  
abstract class cGraficosValoresdb
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
// Retorna los datos de una columna

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarxGraficoxFila($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_gra_graficos_valores_xgraficocod_xfilacod";
		$sparam=array(
			'pfilacod'=> $datos['filacod'],
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los valores del grafico por fila. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna los datos de una columna

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{


		$spnombre="sel_gra_graficos_valores_xgraficocod_columnacod_filacod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod'],
			'pcolumnacod'=> $datos['columnacod'],
			'pfilacod'=> $datos['filacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el valor por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
		


	protected function Insertar($datos)
	{
		$spnombre="ins_gra_graficos_valores";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod'],
			'pcolumnacod'=> $datos['columnacod'],
			'pfilacod'=> $datos['filacod'],
			'pvalor'=> $datos['valor'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el valor. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}		

		return true;
	}
	
		



	protected function Modificar($datos)
	{

		$spnombre="upd_gra_graficos_valores_xgraficocod_columnacod_filacod";
		$sparam=array(
			'pvalor'=> $datos['valor'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pgraficocod'=> $datos['graficocod'],
			'pcolumnacod'=> $datos['columnacod'],
			'pfilacod'=> $datos['filacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el valor. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function EliminarxColumna($datos)
	{

		$spnombre="del_gra_graficos_valores_xgraficocod_columnacod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod'],
			'pcolumnacod'=> $datos['columnacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el valor por columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function EliminarxFila($datos)
	{
		
		$spnombre="del_gra_graficos_valores_xgraficocod_filacod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod'],
			'pfilacod'=> $datos['filacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el valor por fila. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

	protected function EliminarxGrafico($datos)
	{

		$spnombre="del_gra_graficos_valores_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar los valores por grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



}


?>