<?php  
abstract class cGraficosdb
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
	
	protected function BuscarTipos($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_gra_graficos_tipos_xconjuntocod_graficotipoestado";
		$sparam=array(
			'pconjuntocod'=> $datos['conjuntocod'],
			'pgraficotipoestado'=> $datos['graficotipoestado']
			);

	}
//----------------------------------------------------------------------------------------- 
// Retorna el SP y los parametros para cargar los roles del sistema

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarTiposxCodigoxConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gra_graficos_tipos_xgraficotipocod_conjuntocod";
		$sparam=array(
			'pgraficotipocod'=> $datos['graficotipocod'],
			'pconjuntocod'=> $datos['conjuntocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna el SP y los parametros para cargar los roles del sistema

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gra_graficos_busqueda";
		$sparam=array(
			'pxgraficotitulo'=> $datos['xgraficotitulo'],
			'pgraficotitulo'=> $datos['graficotitulo'],
			'pxgraficoestado'=> $datos['xgraficoestado'],
			'pgraficoestado'=> $datos['graficoestado'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar un grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
		
//----------------------------------------------------------------------------------------- 
// Retorna el SP y los parametros para cargar los roles del sistema

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gra_graficos_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el grafico por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
		
		

	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_gra_graficos";
		$sparam=array(
			'pconjuntocod'=> $datos['conjuntocod'],
			'pgraficotipocod'=> $datos['graficotipocod'],
			'pgraficotitulo'=> $datos['graficotitulo'],
			'pgraficoleyendamostrar'=>$datos['graficoleyendamostrar'],
			'pgraficoleyendaalinear'=> $datos['graficoleyendaalinear'],
			'pgraficoleyendaalinearvertical'=> $datos['graficoleyendaalinearvertical'],
			'pgraficoestado'=> $datos['graficoestado'],
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'pgraficofalta'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
		
		
		
		
	protected function Modificar($datos)
	{


		$spnombre="upd_gra_graficos_xgraficocod";
		$sparam=array(
			'pgraficotipocod'=> $datos['graficotipocod'],
			'pgraficoinvertir'=> $datos['graficoinvertir'],
			'pgraficozoom'=> $datos['graficozoom'],
			'pgraficoestilo'=> $datos['graficoestilo'],
			'pgraficoalto'=> $datos['graficoalto'],
			'pgraficomedida'=> $datos['graficomedida'],
			'pgraficotitulo'=> $datos['graficotitulo'],
			'pgraficotituloalign'=> $datos['graficotituloalign'],
			'pgraficodesc'=> $datos['graficodesc'],
			'pgraficodescalign'=> $datos['graficodescalign'],
			'pgraficotitulocolumnas'=> $datos['graficotitulocolumnas'],
			'pgraficotitulocolumnasalign'=> $datos['graficotitulocolumnasalign'],
			'pgraficotitulofilas'=> $datos['graficotitulofilas'],
			'pgraficotitulofilasalign'=> $datos['graficotitulofilasalign'],
			'pgraficofilaflota'=> $datos['graficofilaflota'],
			'pvalorx'=> $datos['valorx'],
			'pvalory'=> $datos['valory'],
			'pgraficoleyendamostrar'=> $datos['graficoleyendamostrar'],
			'pgraficoleyendaalinear'=> $datos['graficoleyendaalinear'],
			'pgraficoleyendaalinearvertical'=> $datos['graficoleyendaalinearvertical'],
			'pgraficomuestravaloreseje'=> $datos['graficomuestravaloreseje'],
			'pgraficomuestravaloresseries'=> $datos['graficomuestravaloresseries'],
			'pgraficoestado'=> $datos['graficoestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar los datos del grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
		


		
	protected function Eliminar($datos)
	{

		$spnombre="del_gra_graficos_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar los datos del grafico. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
		
		
		
		
}


?>