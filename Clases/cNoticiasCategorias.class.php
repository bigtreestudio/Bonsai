<?php 
include(DIR_DATA."noticiasCategoriasData.php");

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias 

class cNoticiasCategorias
{
	protected $conexion;
	protected $datoscategoria;
	protected $noticias;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	private function SetData(&$oData,$datos)
	{
		if (isset($datos['catcod']))
			$oData->setCodigo($datos['catcod']);
		if (isset($datos['planthtmlcod']))
			$oData->setPlantillaHtmlCodigo($datos['planthtmlcod']);
		if (isset($datos['catdominio']))
			$oData->setDominio($datos['catdominio']);
		if (isset($datos['catnom']))
			$oData->setNombre( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['catnom'],ENT_QUOTES));
		if (isset($datos['catdesc']))
			$oData->setDescripcion(FuncionesPHPLocal::ProcesarHtmlCuerpoEditor($this->conexion,$datos['catdesc']));
		if (isset($datos['catsuperior']))
			$oData->setCategoriaSuperior($datos['catsuperior']);
		if (isset($datos['catorden']))
			$oData->setCategoriaOrden( FuncionesPHPLocal::HtmlspecialcharsBigtree($datos['catorden'],ENT_QUOTES));
		if (isset($datos['catestado']))
			$oData->setEstado($datos['catestado']);
		if (isset($datos['catdatajson']))
			$oData->setDataJson($datos['catdatajson']);
		if (isset($datos['menucod']))
			$oData->setMenuCodigo($datos['menucod']);
		if (isset($datos['menutipocod']))
			$oData->setMenuTipoCodigo($datos['menutipocod']);
		if (isset($datos['fondocod']))
			$oData->setFondo($datos['fondocod']);

		return true;
	}



	public function BuscarCategoriaxDominio($datos)
	{
		$spnombre="sel_not_categorias_xcatdominio";
		$sparam=array(
			'pcatdominio'=> $datos['catdominio']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$oCategoriaData = new NoticiasCategoriasData();	
		$datoscategoria = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$this->SetData($oCategoriaData,$datoscategoria);
		
		return $oCategoriaData;	
	}
	
	
	
	public function BuscarCategoriaxCategoria($datos)
	{
		$spnombre="sel_not_categorias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$oCategoriaData = new NoticiasCategoriasData();	
		$datoscategoria = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		return $datoscategoria;	

	}




	public function CargarNoticiasxCategoria(&$oCategoriaData,&$cantidaTotal,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xcatcod_cantidad";
		$sparam=array(
			'pcatcod'=> $oCategoriaData->getCodigo()
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$cantidaTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$cantidaTotal = $datosTotales['total'];			
		}


		$spnombre="sel_not_noticias_publicadas_xcatcod";
		$sparam=array(
			'pcatcod'=> $oCategoriaData->getCodigo(),
			'porderby'=> "noticiafecha DESC",
			'plimit'=> ""
			);

		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$noticias = array();	
		$oNoticiaService = new cNoticias($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oNoticia = new NoticiasData();
			$oNoticiaService->SetData($oNoticia,$fila);
			$noticias[] = $oNoticia;
			unset($oNoticia);
		}
		unset($oNoticiaService);

		$oCategoriaData->setNoticias($noticias);

		return true;	
	}



	public function getFiltros()
	{
		$datos['campojson'] = "muestrafiltro";			
		$datos['valorjson'] = "1";			
		if(!$this->getCategoriasxCampoJson($datos,$resultado,$numfilas))
			return false;
		
		$ArregloCategoriasFiltros = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oCategoriaData = new NoticiasCategoriasData();	
			$this->SetData($oCategoriaData,$fila);
			$ArregloCategoriasFiltros[] = $oCategoriaData;
			unset($oCategoriaData);
		}
		return $ArregloCategoriasFiltros;		
	}



	public function getHijos($datos=array())
	{
		if (count ($datos)==0)
			$datos = $this->datoscategoria;
		$arreglodatos = array();
		$this->getHijosRecursiva($datos,$arreglodatos);
		return $arreglodatos;
	}



	public function getCategoriaPadreRaiz($datos=array(),&$catsuperior)
	{
		if (count($datos)==0)
			$datos = $this->datoscategoria;
			
		if ($datos['catsuperior']!="")	
		{
			if (!$this->getCategoriaPadreRaiz($datos,$resultado,$numfilas))
				return false;
			$datoscategoriasuperior = $this->conexion->ObtenerSiguienteRegistro($resultado);
			if (!$this->getCategoriaPadreRaiz($datoscategoriasuperior))
				return false;
		}else
			$catsuperior = $datos['catcod'];
		
		return true;	
	}
	




	private function getHijosRecursiva($datos,&$arreglodatos)
	{
		$datosbusqueda['catsuperior'] = $datos['catcod'];
		if(!$this->getCategoriaxSuperior($datosbusqueda,$resultado,$numfilas))
			return false;
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$arreglodatos[] = $fila;
			if(!$this->getHijosRecursiva($fila,$arreglodatos))
				return false;
		}
		return true;
	}



	private function getCategoriaxSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_categorias_xcatsuperior";
		$sparam=array(
			'pcatsuperior'=> $datos['catsuperior']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;			
	}
			


	private function getCategoriasxCampoJson($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_categorias_xcampojson";
		$sparam=array(
			'pcampojson'=> $datos['campojson'],
			'pvalorjson'=> $datos['valorjson']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las categorias por datos en json.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;			
	}
			



}//FIN CLASE

?>