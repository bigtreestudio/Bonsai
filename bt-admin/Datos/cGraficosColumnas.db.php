<?php  
abstract class cGraficosColumnasdb
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
	
	protected function BuscarxCodigoxGrafico($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_gra_graficos_columnas_xcolumnacod_graficocod";
		$sparam=array(
			'pcolumnacod'=> $datos['columnacod'],
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las columnas por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
	
	protected function BuscarxGrafico($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_gra_graficos_columnas_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las columnas por grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
		


	protected function BuscarColumnaUltimoOrden($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_gra_graficos_columas_maxorden_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el maximo por grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
		



	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_gra_graficos_columnas";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod'],
			'pcolumnatitulo'=> $datos['columnatitulo'],
			'pcolumnaorden'=> $datos['columnaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
			
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
		



	protected function Modificar($datos)
	{
		$spnombre="upd_gra_graficos_columnas_xcolumnacod";
		$sparam=array(
			'pcolumnatitulo'=> $datos['columnatitulo'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcolumnacod'=> $datos['columnacod']
			);			
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
		


	protected function ModificarOrden($datos)
	{
		$spnombre="upd_gra_graficos_columnas_orden_xcolumnacod";
		$sparam=array(
			'pcolumnaorden'=> $datos['columnaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pcolumnacod'=> $datos['columnacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
		





	protected function Eliminar($datos)
	{
		$spnombre="del_gra_graficos_columnas_xcolumnacod_graficocod";
		$sparam=array(
			'pcolumnacod'=> $datos['columnacod'],
			'pgraficocod'=> $datos['graficocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
		

	protected function EliminarxGrafico($datos)
	{

		$spnombre="del_gra_graficos_columnas_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);

	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar las columnas por grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}





}


?>