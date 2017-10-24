<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cGraficos
{
	protected $conexion;
	protected $datosgrafico;

	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	public function BuscarGraficoxCodigo($datos)
	{
		$spnombre="sel_gra_graficos_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $datos['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el grafico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$this->datosgrafico = $this->conexion->ObtenerSiguienteRegistro($resultado);

		return true;	
	}


	public function Mostrar($datos=array(),&$html)
	{
		$datos['graficocod'] = $datos['Codigo'];
		if($this->BuscarGraficoxCodigo($datos))
		{
			if ($this->datosgrafico['conjuntocte']=="GRAFVALORES")
				$html .= $this->CargarJsGraficosDobleEje();
			else
				$html .= $this->CargarJsGraficosEjeSimple();
		}
		return true;
	}


	private function CargarJsGraficosDobleEje()
	{
		
		$columnas = $this->BuscarColumnas();
		$filas = $this->BuscarFilas();
		$categories = array();
		$arreglocategorias = array();
		if ($columnas['cantidad']>0)
		{
			foreach($columnas['datos'] as $datoscol)	
			{
				$categories[$datoscol['columnacod']] = $datoscol['columnatitulo'];
				$arreglocategorias[$datoscol['columnacod']] = $datoscol;
			}
		}

		$arreglovalores = array();
		$arreglodatosmostrar = array();
		if ($filas['cantidad']>0)
		{
			$i=0;
			foreach($filas['datos'] as $datosfila)	
			{
				$datosvalores = $this->BuscarValoresxFila($datosfila['filacod']);
				foreach($datosvalores['datos'] as $dataval)
					$arreglovalores[$dataval['columnacod']] = $dataval['valor'];
					
				$arreglodatosmostrar[$i]['name'] = utf8_encode($datosfila['filatitulo']);
				$arreglodatosmostrar[$i]['color'] = $datosfila['filacolor'];
				foreach($arreglocategorias as $datoscolumna)
				{
					$valores = 0;
					if (array_key_exists($datoscolumna['columnacod'],$arreglovalores))
						$valores = $arreglovalores[$datoscolumna['columnacod']];
						
					if ($valores!="")
						$arreglodatosmostrar[$i]['data'][] = $valores;
					else
						$arreglodatosmostrar[$i]['data'][] = NULL;
				}  
				$i++;
			}
		}
		
		$jsongrafico = json_encode($this->datosgrafico);
		$jsongrafico = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $jsongrafico);

		$json = json_encode($arreglodatosmostrar);
		$json = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $json);
		$codigo = $this->datosgrafico['graficocod'];
		$html = '<script type="text/javascript">';
		$html .= 'var categories_'.$codigo.' = '.FuncionesPHPLocal::js_array($categories).';';
		$html .= 'var seriescarga_'.$codigo.' = '.$json.';';
		$html .= 'var datosgrafico_'.$codigo.' = '.$jsongrafico.';';
		$html .= 'jQuery(document).ready(function(){';
		$html .= '	GraficoBarras("'.$this->datosgrafico['graficotipovalor'].'",'.$codigo.',categories_'.$codigo.',seriescarga_'.$codigo.',datosgrafico_'.$codigo.');';
		$html .= '});';
		$html .= '</script>';
		$html .= '<div id="container_'.$codigo.'"></div>';
		$html .= '<noscript>gr&aacute;fico animado de '. FuncionesPHPLocal::HtmlspecialcharsBigtree($this->datosgrafico['graficotitulo'],ENT_QUOTES).'</noscript>';

		return $html;
	}




	private function CargarJsGraficosEjeSimple()
	{
		
		$filas = $this->BuscarFilas();
		$arreglotitulos = array();
		$datosvalores = $this->BuscarValoresUnEjexGrafico();

		$arreglovalores = array();
		foreach($datosvalores['datos'] as $dataval)
			$arreglovalores[$dataval['filacod']] = $dataval['valor'];
		
		
		$arreglodatosmostrar = array();
		$arreglo = array();
		$arreglocomunes = array();
		if ($filas['cantidad']>0)
		{
			$i=0;
			foreach($filas['datos'] as $datosfila)	
			{
				$valores = 0;
				if (array_key_exists($datosfila['filacod'],$arreglovalores))
					$valores = $arreglovalores[$datosfila['filacod']];
							
				if ($valores!="")
					$valormostrar = $valores;
				else
					$valormostrar = NULL;
				
				$arreglotitulos[]=utf8_encode($datosfila['filatitulo']);
				$arreglocarga['name']= utf8_encode($datosfila['filatitulo']);
				$arreglocarga['color']= $datosfila['filacolor'];
				$arreglocarga['y']= $valormostrar;
			
				$arreglo[] = $arreglocarga;
				$arreglocomunes[$i] = $arreglocarga;
				$i++;	
			}
		}


		if ($this->datosgrafico['graficotipovalor']=="pie")
		{
			$arreglodatosmostrar['data']=$arreglo;
			$arreglodatosmostrar['type']=$this->datosgrafico['graficotipovalor'];
		}else
		{
			$arreglodatosmostrar['data']=$arreglocomunes;
			$arreglodatosmostrar['type']=$this->datosgrafico['graficotipovalor'];
		}

		
		$jsongrafico = json_encode($this->datosgrafico);
		$jsongrafico = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $jsongrafico);

		$json = json_encode($arreglodatosmostrar);
		$json = preg_replace( '/"(-?\d+\.?\d*)"/', '$1', $json);
		$codigo = $this->datosgrafico['graficocod'];

		$html = '<script type="text/javascript">';
		$html .= 'var categories_'.$codigo.' = '.FuncionesPHPLocal::js_array($arreglotitulos).';';
		$html .= 'var seriescarga_'.$codigo.' = ['.$json.'];';
		$html .= 'var datosgrafico_'.$codigo.' = '.$jsongrafico.';';
		$html .= 'jQuery(document).ready(function(){';
		$html .= '	GraficoPorcentajes("'.$this->datosgrafico['graficotipovalor'].'",'.$codigo.',categories_'.$codigo.',seriescarga_'.$codigo.',datosgrafico_'.$codigo.');';
		$html .= '});';
		$html .= '</script>';
		$html .= '<div id="container_'.$codigo.'"></div>';
		$html .= '<noscript>gr&aacute;fico animado de '. FuncionesPHPLocal::HtmlspecialcharsBigtree($this->datosgrafico['graficotitulo'],ENT_QUOTES).'</noscript>';
		return $html;
	}




	private function BuscarFilas()
	{
		$spnombre="sel_gra_graficos_filas_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $this->datosgrafico['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el grafico por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$arreglodatos['cantidad'] = $numfilas;
		$arreglodatos['datos'] = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arreglodatos['datos'][] = $fila;
			
		return $arreglodatos;	
	}

	
	private function BuscarColumnas()
	{
		$spnombre="sel_gra_graficos_columnas_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $this->datosgrafico['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el grafico por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$arreglodatos['cantidad'] = $numfilas;
		$arreglodatos['datos'] = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arreglodatos['datos'][] = $fila;
			
		return $arreglodatos;	
	}


	private function BuscarValoresxFila($fila)
	{
		$spnombre="sel_gra_graficos_valores_xfilacod";
		$sparam=array(
			'pgraficocod'=> $this->datosgrafico['graficocod'],
			'pfilacod'=> $fila
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los valores de la fila del grafico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$arreglodatos['cantidad'] = $numfilas;
		$arreglodatos['datos'] = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arreglodatos['datos'][] = $fila;
			
		return $arreglodatos;	
	}

	private function BuscarValoresUnEjexGrafico()
	{
		$spnombre="sel_gra_graficos_valores_porcentajes_xgraficocod";
		$sparam=array(
			'pgraficocod'=> $this->datosgrafico['graficocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los valores de un eje del grafico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$arreglodatos['cantidad'] = $numfilas;
		$arreglodatos['datos'] = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arreglodatos['datos'][] = $fila;
			
		return $arreglodatos;	
	}

	
			
}//FIN CLASE

?>